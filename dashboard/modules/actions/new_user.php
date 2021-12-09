<?php

class MainAction extends DashAction {

  public function getName(){
    return "New user";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return true;
  }

  public function performAction($dashboard){


    $con = $dashboard->getConnection();
    $cm = $dashboard->getContentManager();
    //The user's password has changed
    $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $query = 'INSERT INTO '.$cm->getUsersTableString().' (username, email, password, password_salted, role, login_attempts) VALUES (:username, :email, :password, :password_salted, :role, :login_attempts)';
    $values = array(":username" => $_POST['username'], ":email" => $_POST['email'], ":password" => $password, ":password_salted" => 1, ":role" => $_POST['role'], ":login_attempts" => 0);

    $stmt = $con->prepare($query);

    $res = $stmt->execute($values);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "The user has been created successfully.", "location" => DashboardLinks::MANAGE_USERS_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "The user cannot be created at this time.");
    }



  }

}


?>
