<?php

class MainView extends DashView{

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

  public function generateView($dashboard){

    DashHelperFunctions::createTmpLocation();

    $cm = $dashboard->getContentManager();

    $form = new DashForm(DashboardLinks::NEW_CONTENT_ACTION);
    $form->setTitle("New content");
    $form->isAjax(true);

    $form->createNewSection();
    $form->addPill("Title", "text", "title", "A short title about the content");
    $form->addPill("Introduction", "textarea", "introduction", "An introduction describing the content");
    $form->addDropdownPill("Category", "category", "Select a category", "", "", $cm->getCategories(true));

    $form->addPill("Date", "date", "date", "Select a date", "date_picker", "", date("Y-m-d"));
    $form->addPill("Time", "time", "time", "Select a time", "time_picker", "", date("H:i"));
    $form->addPill("Tags (separated by commas)", "textarea", "tags", "Tags describing the content of the post (separated by commas)");
    $form->addPill("Classes (separated by spaces)", "textarea", "classes", "Classes can be applied to posts individually by adding them here");

    $form->createNewSection();
    $form->addContentEditor("", "Post content");

    $form->createNewSection();
    $form->addHtml("<p>Publishing this post</p>");
    $form->addPill("Publish publicly", "radio", "status", "", "", "checked", "2");
    $form->addPill("Publish privately", "radio", "status", "", "", "", "1");
    $form->addPill("Save and don't publish", "radio", "status", "", "", "", "0");

    $form->addHtml('<table class="table_pill"><tr><td>');
    $form->addDropdownPill("Banner image", "banner_image", "Post image", "", "banner_img", array("" => "None"), "");
    $form->addHtml("</td><td>");
    $form->addButton('<span class="icon icon-glyphicons-82-refresh"></span>', "reload_assets", "reload_button");
    $form->addHtml("</td></tr></table>");

    $form->addSubmitButton("Post content", "success");

    $form->generate();

  }

}


?>
