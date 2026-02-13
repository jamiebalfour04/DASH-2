<?php

class MainAction extends DashAction {

  public function getName(){
    return "Delete content";
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

    //Non-admin/editor users cannot edit other users posts, but admins/editors can edit anyones posts
    if(!($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
      $query = 'DELETE FROM '.$cm->getPostsTableString().' WHERE friendly_name=:friendly_name AND poster=:poster';
    } else {
      $query = 'DELETE FROM '.$cm->getPostsTableString().' WHERE friendly_name=:friendly_name';
    }


    //Start preparing the data for entry
    $poster = $dashboard->getDashboardUser()->getUserID();

    //Set up the info we need, we may not need the poster value to be set
    $items = array(
      ":friendly_name" => $_POST['post_id']
    );

    //These users don't need a poster ID to verify they can edit this post
    if(!($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
      $items[":poster"] = $poster;
    }

    $stmt = $con->prepare($query);

    $res = $stmt->execute($items);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Content deleted successfully.", "location" => DashboardLinks::PREVIEW_CONTENT_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "Content cannot be deleted at this time.");
    }



  }

}


?>
