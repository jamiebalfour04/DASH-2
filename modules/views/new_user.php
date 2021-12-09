<?php
class MainView extends DashView{

  public function getName(){
    return "New user account";
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

  public function generateView($dashboard){

    $cm = $dashboard->getContentManager();
    $form = new DashForm(DashboardLinks::NEW_USER_ACTION);

    $form->setTitle("Create DASH user account");
    $form->isAjax(true);

    $form->addPill("Username (maximum of 30 characters)", "text", "username", "Username", "", 'maxlength="10"');
    $form->addPill("User's email address (maximum of 50 characters)", "email", "email", "User's email address");
    $form->addPill("User's password", "text", "new_password", "User's password");
    $form->addDropdownPill("DASH Role", "role", "Select a role", "", "", array(Dashboard::ADMINISTRATOR_ROLE => "Administrator", Dashboard::EDITOR_ROLE => "Editor", Dashboard::WRITER_ROLE => "Writer"), Dashboard::WRITER_ROLE);
    $form->addSubmitButton("Create user", "success");
    $form->generate();
  }

}


?>
