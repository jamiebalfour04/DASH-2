<?php
class MainView extends DashView{

  public function getName(){
    return "Manage your account";
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

    $cm = $dashboard->getContentManager();
    $form = new DashForm(DashboardLinks::MANAGE_ACCOUNT_ACTION);

    $form->setTitle("Manage your DASH account");
    $form->isAjax(true);

    $form->addPill("Display name", "text", "display_name", "Display name", "", "", $dashboard->getDashboardUser()->getDisplayName());
    $form->addPill("Your email address", "text", "email", "Your email address", "", "", $dashboard->getDashboardUser()->getEmailAddress());
    $form->addParagraph("You can update your password by entering a new one here and clicking save.");
    $form->addPill("Change password", "password", "new_password", "Your password");
    $form->addSubmitButton("Save", "success");
    $form->generate();
  }

}


?>
