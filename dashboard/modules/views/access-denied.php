<?php

class MainView extends DashView{

  private $post = null;

  public function getName(){
    return "Access denied";
  }

  public function requiresLogin(){
    return false;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function __construct($dashboard){

  }

  public function generateView($dashboard){

    echo '<h1>Access denied</h1>';
    echo '<div class="center_align">';
    echo '<p style="font-size:140%">You either need to login or do not have access rights to this part of the
    dashboard. Please contact the administrator of this DASH installation.</p>';
    echo '<a href="'.DashboardLinks::DASHBOARD_VIEW.'" class="button">Go home</a>';
    echo '</div>';


  }

}


?>
