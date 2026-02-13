<?php

class MainAction extends DashAction {

  public function getName(){
    return "Edit content";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function performAction($dashboard){

    $con = $dashboard->getConnection();
    $cm = $dashboard->getContentManager();

    $friendly_name = $_POST['post_id'];

    $post = $cm->fetchPostByName($friendly_name);
    $old_year = date("Y", strtotime($post->getDate()));
    $old_month = date("m", strtotime($post->getDate()));

    //Non-admin/editor users cannot edit other users posts, but admins/editors can edit anyones posts
    if(!($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
      $query = 'UPDATE '.$cm->getPostsTableString().' SET title=:title, introduction=:introduction, banner=:banner, content=:content, date=:date, category=:category, tags=:tags, classes=:classes, friendly_name=:new_friendly_name, status=:status WHERE friendly_name=:friendly_name AND poster=:poster';
    } else {
      $query = 'UPDATE '.$cm->getPostsTableString().' SET title=:title, introduction=:introduction, banner=:banner, content=:content, date=:date, category=:category, tags=:tags, classes=:classes, friendly_name=:new_friendly_name, status=:status WHERE friendly_name=:friendly_name';
    }


    //Start preparing the data for entry
    $title = htmlentities($_POST['title']);
    $introduction = htmlentities($_POST['introduction']);
    $banner = $_POST['banner_image'];
    $content = $_POST['content'];
    $date = $_POST['date'] . ' ' . $_POST['time'];
    $category = $_POST['category'];
    $poster = $dashboard->getDashboardUser()->getUserID();
    $tags = $_POST['tags'];
    $classes = $_POST['classes'];
    $new_friendly_name = DashHelperFunctions::generateFriendlyName($_POST['title']);
    $status = $_POST['status'];


    //Move all uploaded assets from the tmp folder to their correct location
    $assets_path = $cm->getPostAssetsPath($post);

    $new_year = date("Y", strtotime($date));
    $new_month = date("m", strtotime($date));

    if($friendly_name != $new_friendly_name || ($old_year != $new_year) || ($old_month != $new_month)){
      //Move if the date or friendly name changed
      $old_path = $_SERVER['DOCUMENT_ROOT'] . $assets_path . '/' . $old_year . '/' . $old_month . '/' . $friendly_name;
      $new_path = $_SERVER['DOCUMENT_ROOT'] . $assets_path . '/' . $new_year . '/' . $new_month . '/' . $new_friendly_name;

      if(file_exists($old_path)){
        rename(realpath($old_path), realpath($new_path));
      }
    }

    $tmp_path = DashHelperFunctions::getTmpLocation();//DASHBOARD_PATH . 'tmp/' . $_SESSION['tmp_location'];



    if($tmp_path !== false && file_exists($_SERVER['DOCUMENT_ROOT'] . $tmp_path)){
      $new_path = $assets_path;// . '/' . $new_year . '/' . $new_month . '/' . $new_friendly_name;
      $new_path = DashHelperFunctions::removeDuplicateSlashes($new_path);

      $assets = glob($_SERVER['DOCUMENT_ROOT'] . $tmp_path . '/*');

      if(!file_exists($_SERVER['DOCUMENT_ROOT'] .  $new_path)){
        mkdir($_SERVER['DOCUMENT_ROOT'] .  $new_path, 0755, true);
      }

      //Move each asset
      foreach($assets as $asset){
        //Replace the tmp directory in the asset path in the contents of the post
        $content = str_replace($tmp_path, $new_path, $content);
        $banner = str_replace($tmp_path, $new_path, $banner);
        rename($asset, $_SERVER['DOCUMENT_ROOT']. $new_path . '/' . basename($asset));
      }

      //Clean up
      rmdir($_SERVER['DOCUMENT_ROOT'] . '/' . $tmp_path);
      unset($_SESSION['tmp_location']);
    }

    //Set up the info we need, we may not need the poster value to be set
    $items = array(
      ":friendly_name" => $friendly_name,
      ":title" => $title,
      ":introduction" => $introduction,
      ":banner" => $banner,
      ":content" => $content,
      ":date" => $date,
      ":category" => $category,
      ":tags" => $tags,
      ":classes" => $classes,
      ":new_friendly_name" => $new_friendly_name,
      ":status" => $status
    );

    //These users don't need a poster ID to verify they can edit this post
    if(!($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
      $items[":poster"] = $poster;
    }

    $stmt = $con->prepare($query);

    $res = $stmt->execute($items);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Content updated successfully.", "location" => DashboardLinks::PREVIEW_CONTENT_VIEW.$new_friendly_name);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "Content be edited at this time.");
    }



  }

}


?>
