<?php

class MainView extends DashView{

  public function getName(){
    return "Help";
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

  ?>

    <h1>Welcome to DASH Help</h1>
    <p>
      This help is designed to be simple and as a result is very basic. You will
      not find much more than information about your installation here.
    </p>
    <p>
      If you need actual <strong>help</strong>, please visit the official documentation
      when it is completed.
    </p>
    <div class="center_align">
      <a class="button" href="https://www.jamiebalfour.scot/projects/dash/">Visit the DASH website</a>
    </div>

  <?php

  }

}


?>
