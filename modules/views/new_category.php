<?php

class MainView extends DashView{

  public function getName(){
    return "New category";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return true;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function generateView($dashboard){

    $cm = $dashboard->getContentManager();

    $form = new DashForm(DashboardLinks::NEW_CATEGORY_ACTION);
    $form->setTitle("New category");
    $form->isAjax(true);

    $form->addParagraph("You can rename categories from here.");
    $form->addPill("Title", "text", "title", "Category title");
    $form->addSubmitButton("Create category", "success");

    $form->generate();

  }

}


?>
