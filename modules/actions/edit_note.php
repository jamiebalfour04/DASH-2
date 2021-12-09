<?php

class MainAction extends DashAction {

  public function getName(){
    return "Edit note";
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
    $query = 'UPDATE '.$cm->getNotesTableString().' SET title=:title, content=:content, modified=:modified WHERE note_id=:note_id AND user_id=:user_id';



    //Start preparing the data for entry
    $title = htmlentities($_POST['title']);
    $content = $_POST['content'];
    $user_id = $dashboard->getDashboardUser()->getUserID();

    //Set up the info we need, we may not need the poster value to be set
    $items = array(
      ":note_id" => $_POST['note_id'],
      ":title" => $title,
      ":content" => $content,
      ":user_id" => $user_id,
      ":modified" => date("Y-m-d H:i:s")
    );

    $stmt = $con->prepare($query);

    $res = $stmt->execute($items);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Note updated successfully.", "location" => DashboardLinks::MANAGE_NOTES_VIEW.$note_id);
    } else{
      //If there was an error, let the user know
      return array("success" => 1, "message" => "Note be edited at this time.");
    }



  }

}


?>
