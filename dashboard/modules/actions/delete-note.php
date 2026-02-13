<?php

class MainAction extends DashAction {

  public function getName(){
    return "Delete note";
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

    $query = 'DELETE FROM '.$cm->getNotesTableString().' WHERE note_id=:note_id AND user_id=:user_id';

    //Start preparing the data for entry
    $user_id = $dashboard->getDashboardUser()->getUserID();

    //Set up the info we need, we may not need the poster value to be set
    $items = array(
      ":note_id" => $_POST['note_id'],
      ":user_id" => $user_id
    );

    $stmt = $con->prepare($query);

    $res = $stmt->execute($items);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Note deleted successfully.", "location" => DashboardLinks::MANAGE_NOTES_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "Note cannot be be deleted at this time.");
    }



  }

}


?>
