<?php

class MainView extends DashView{

  public function getName(){
    return "Export content";
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

    DashHelperFunctions::createTmpLocation();

    $cm = $dashboard->getContentManager();

    $form = new DashForm(DashboardLinks::EXPORT_ACTION);
    $form->setTitle("Export content");

    $form->addHtml("<p>Select which tables to export</p>");
    $form->addPill("Post content table", "checkbox", "posts");
    $form->addPill("Categories table", "checkbox", "categories");
    $form->addPill("Users table", "checkbox", "users");
    $form->addPill("Notes table", "checkbox", "notes");

    $form->addSubmitButton("Export content", "success");

    $form->generate();

  }

}


?>
