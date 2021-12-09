<?php
class MainView extends DashView{

  public function getName(){
    return "Search results";
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

  public function generateView($dashboard){
    $user = $dashboard->getDashboardUser();

    $form = new DashForm(DashboardLinks::LOGOUT_ACTION);

    $form->center(true);
    $form->setTitle("Logout");

    $form->addHtml("<p>Are you sure you wish to logout?</p>");
    $form->addSubmitButton("Logout now");
    $form->generate();
  }
}
?>
