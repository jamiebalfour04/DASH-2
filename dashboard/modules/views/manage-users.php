<?php

class MainView extends DashView{

  public function getName(){
    return "Manage users";
  }

  public function requiresLogin(){
    return false;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return true;
  }

  public function __construct($dashboard){

  }

  public function generateView($dashboard){


    $cm = $dashboard->getContentManager();

    if(!isset($_GET['data'])){
      //List all users
      $page_number = 1;
      if(isset($_GET['page_number'])){
        $page_number = $_GET['page_number'];
      }

      $user = $dashboard->getDashboardUser();


      $users = $cm->getUsers();

      $form = new DashForm();

      $form->setTitle("Manage users");

      $form->addParagraph("Use this form to update users. Note that administrator users cannot be changed from here.");



      $form->addHtml('<table class="table_flow responsive"><tr><th style="width:200px;">Username</th><th>User type</th><th>&nbsp;&nbsp;</th>');
      foreach($users as $user){
        if($user->getRole() != Dashboard::ADMINISTRATOR_ROLE){
          $form->addHtml('<tr>');
        } else{
          $form->addHtml('<tr class="disabled">');
        }
        $form->addHtml('<td>'.$user->getUsername().'</td>');
        $form->addHtml('<td>'.DashHelperFunctions::roleToString($user->getRole()).'</td>');
        if($user->getRole() != Dashboard::ADMINISTRATOR_ROLE){
          $form->addHtml('<td style="text-align:right;"><a class="button" href="'.DashboardLinks::MANAGE_USERS_VIEW.$user->getUserId().'">Change user</a></td></tr>');
        } else{
          $form->addHtml('<td style="text-align:right;">This user cannot be modified.</td></tr>');
        }
      }
      $form->addHtml('</table>');

      $form->addCenteredButton("Create new user", "", "", DashboardLinks::NEW_USER_VIEW);

      $form->generate();
      return;
    } else{
      $user = $cm->getUserFromID($_GET['data']);
      if($user->getRole() != Dashboard::ADMINISTRATOR_ROLE){
        $form = new DashForm(DashboardLinks::MANAGE_USERS_ACTION);

        $form->addHiddenInput("user_id", $_GET['data']);
        $form->setTitle("Manage DASH account for " . $user->getUsername());
        $form->isAjax(true);

        $form->addPill("User's email address (maximum of 50 characters)", "text", "email", "Your email address", "", "", $user->getEmailAddress());
        $form->addDropdownPill("DASH Role", "role", "Select a role", "", "", array(Dashboard::ADMINISTRATOR_ROLE => "Administrator", Dashboard::EDITOR_ROLE => "Editor", Dashboard::WRITER_ROLE => "Writer"), $user->getRole());
        $form->addParagraph("You can update the user's password by entering a new one here and clicking save.");
        $form->addPill("Set password", "password", "new_password", "Your password");
        $form->addSubmitButton("Save", "success");

        $form->generate();
      } else{
        $form = new DashForm();
        $form->setTitle("Access denined");
        $form->addParagraph("This user is an administrator and therefore cannot be modified.");
        $form->generate();
      }
    }

  }

}


?>
