<?php

class MainView extends DashView{

  public function getName(){
    return "New note";
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

    DashHelperFunctions::createTmpLocation();

    $cm = $dashboard->getContentManager();

    $form = new DashForm(DashboardLinks::NEW_NOTE_ACTION);
    $form->setTitle("New note");
    $form->isAjax(true);

    $form->addPill("Title", "text", "title", "A short title about the content");
    $form->addContentEditor("", "Note content");

    $form->addSubmitButton("Save note", "success");

    $form->generate();

  }

}


?>
