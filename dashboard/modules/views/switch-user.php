<?php
class MainView extends DashView{

  private $post = null;

  public function getName(){
    return "Switch User";
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

    $con = $dashboard->getConnection();

    $cm = $dashboard->getContentManager();


    $form = new DashForm(DashboardLinks::SWITCH_USER_ACTION);

    //Find all users that are not admins and not this user
    $usernames = array();
    foreach($cm->getUsers() as $user){
      if($user->getRole() != Dashboard::ADMINISTRATOR_ROLE){
        $usernames[$user->getUserId()] = $user->getUsername();
      }
    }


    $form->center(true);
    $form->setTitle("Switch User");

    $form->addParagraph("You can switch to another non-administrator user on this installation.");
    if(count($usernames) > 0){
      $form->addDropdownPill("Username", "user_id", "Username for user to switch to", "", "", $usernames);
      $form->addSubmitButton("Switch user", "success");
    } else{
      $form->addParagraph("<strong>There are no other users to switch to.</strong>");
    }



    $form->generate();

  }

}


?>
