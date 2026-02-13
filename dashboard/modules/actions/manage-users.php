<?php

class MainAction extends DashAction {

  public function getName(){
    return "Manage users";
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
    if($_POST['new_password'] != ""){
      $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
      $query = 'UPDATE '.$cm->getUsersTableString().' SET email=:email, password=:password, role=:role WHERE user_id = :user_id AND role != 0';
      $values = array(":email" => $_POST['email'], ":password" => $password, ":user_id" => $_POST['user_id'], ":role" => $_POST['role']);

    } else {
      $query = 'UPDATE '.$cm->getUsersTableString().' SET email=:email, role=:role WHERE user_id = :user_id AND role != 0';
      $values = array(":email" => $_POST['email'], ":user_id" => $_POST['user_id'], ":role" => $_POST['role']);

    }
    $current_user = $dashboard->getDashboardUser();
    //If they've modified themselves
    if($_POST['user_id'] == $current_user->getUserID()){
      //Update the logged in user
      $user_obj = new DashUser();
      $user_obj->setData($current_user->getUserID(), $current_user->getUsername(), $_POST['email'], $_POST['role']);

      $_SESSION['DASH_LOGIN'] = array("CONFIG" => $_SESSION['DASH_LOGIN']['CONFIG'], "USER" => serialize($user_obj));
    }



    $stmt = $con->prepare($query);

    $res = $stmt->execute($values);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "The user has been updated successfully.", "location" => DashboardLinks::MANAGE_USERS_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 0, "message" => "The user cannot be updated at this time.");
    }



  }

}


?>
