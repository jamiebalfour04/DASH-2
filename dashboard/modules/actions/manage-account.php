<?php

class MainAction extends DashAction {

  public function getName(){
    return "Manage account";
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
    $current_user = $dashboard->getDashboardUser();
    //The user has changed their password
    if($_POST['new_password'] != ""){
      $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
      $query = 'UPDATE '.$cm->getUsersTableString().' SET email=:email, password=:password, WHERE user_id = :user_id';
      $values = array(":email" => $_POST['email'], ":password" => $password, ":user_id" => $current_user->getUserID());

    } else {
      $query = 'UPDATE '.$cm->getUsersTableString().' SET email=:email WHERE user_id = :user_id';
      $values = array(":email" => $_POST['email'], ":user_id" => $dashboard->getDashboardUser()->getUserID());

    }

    //Update the logged in user
    $user_obj = new DashUser();
    $user_obj->setData($current_user->getUserID(), $current_user->getUsername(), $_POST['email'], $current_user->getRole());

    $_SESSION['DASH_LOGIN'] = array("CONFIG" => $_SESSION['DASH_LOGIN']['CONFIG'], "USER" => serialize($user_obj));

    $stmt = $con->prepare($query);

    $res = $stmt->execute($values);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Your account has been updated successfully.", "location" => DashboardLinks::MANAGE_ACCOUNT_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "Your account cannot be updated at this time.");
    }



  }

}


?>
