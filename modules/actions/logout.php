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

    unset($_SESSION['DASH_LOGIN']);
    header("Location:" . DashboardLinks::LOGIN_VIEW);
    exit;

  }

}


?>
