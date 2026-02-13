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

    $query = "INSERT INTO " . $dashboard->getNotesTableString() . ' (title, content, time, modified, user_id) VALUES (:title, :content, :time, :modified, :user_id)';

    $stmt = $con->prepare($query);

    $res = $stmt->execute(array(":title" => $_POST['title'], ":content" => $_POST['content'], ":time" => date("Y-m-d H:i:s"), ":modified" => date("Y-m-d H:i:s"), ":user_id" => $dashboard->getDashboardUser()->getUserID()));
    if($res){
      return array("success" => 1, "message" => "Note created successfully.", "location" => DashboardLinks::MANAGE_NOTES_VIEW);
    } else{
      return array("success" => 0, "message" => "Note cannot be created at this time.");
    }
  }

}


?>
