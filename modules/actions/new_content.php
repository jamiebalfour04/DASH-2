<?php

class MainAction extends DashAction {

  public function getName(){
    return "New content";
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

    $query = 'INSERT INTO '.$cm->getPostsTableString().' (title, introduction, banner, content, date, category, poster, tags, friendly_name, status) VALUES (:title, :introduction, :banner, :content, :date, :category, :poster, :tags, :friendly_name, :status)';


    $title = htmlentities($_POST['title']);
    $introduction = htmlentities($_POST['introduction']);
    $banner = $_POST['banner_image'];
    $content = $_POST['content'];
    $date = $_POST['date'] . ' ' . $_POST['time'];
    $category = $_POST['category'];
    $poster = $dashboard->getDashboardUser()->getUserID();
    $tags = $_POST['tags'];
    $friendly_name = DashHelperFunctions::generateFriendlyName($_POST['title']);
    $status = $_POST['status'];
    $send_notification = false;

    if($dashboard->getConfigOption("send_push_notifications") == true){
      $send_notification = true;
    }


    $tmp_path = DashHelperFunctions::getTmpLocation();

    //If nothing was uploaded then a tmp_path won't even exist.
    if($tmp_path !== false && file_exists($_SERVER['DOCUMENT_ROOT'] . $tmp_path)){

      //Move all uploaded assets from the tmp folder to their correct location
      $assets_path = $cm->getAssetsDirectory();


      $new_path = $assets_path . '/' . date("Y", strtotime($date)) . '/' . date("m", strtotime($date)) . '/' . $friendly_name;

      $new_path = DashHelperFunctions::removeDuplicateSlashes($new_path);


      $assets = glob($_SERVER['DOCUMENT_ROOT'] . $tmp_path . '/*');



      if(!file_exists($_SERVER['DOCUMENT_ROOT'] .  $new_path)){
        mkdir($_SERVER['DOCUMENT_ROOT'] .  $new_path, 0755, true);
      }

      //Move each asset
      foreach($assets as $asset){
        //Replace the tmp directory in the asset path in the contents of the post
        $content = str_replace($tmp_path, $new_path, $content);
        rename($asset, $_SERVER['DOCUMENT_ROOT']. $new_path . '/' . basename($asset));
      }

      //Clean up
      rmdir($_SERVER['DOCUMENT_ROOT'] . '/' . $tmp_path);
      unset($_SESSION['tmp_location']);
    }


    $items = array(
      ":title" => $title,
      ":introduction" => $introduction,
      ":banner" => $banner,
      ":content" => $content,
      ":date" => $date,
      ":category" => $category,
      ":poster" => $poster,
      ":tags" => $tags,
      ":friendly_name" => $friendly_name,
      ":status" => $status
    );

    $stmt = $con->prepare($query);



    $res = $stmt->execute($items);
    if($res){

      if($send_notification){
        $app_id = $dashboard->getConfigOption("onesignal_app_id");
        $rest_id = $dashboard->getConfigOption("onesignal_rest_id");
        $manager = new DashPushNotificationManager($app_id, $rest_id);

        $manager->sendPushNotification(array("en" => "Test post"), array("en" => "Testing"));
      }
      return array("success" => 1, "message" => "Content created successfully.", "location" => DashboardLinks::DASHBOARD_VIEW);
    } else{
      return array("success" => 0, "message" => "Content be created at this time.");
    }



  }

}


?>
