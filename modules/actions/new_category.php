<?php

class MainAction extends DashAction {

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

  public function performAction($dashboard){

    $con = $dashboard->getConnection();
    $cm = $dashboard->getContentManager();

    //Start preparing the data for entry
    $title = htmlentities($_POST['title']);

    //Set up the info we need, we may not need the poster value to be set
    $items = array(
      ":title" => $title,
      ":friendly_name" => DashHelperFunctions::generateFriendlyName($title)
    );

    $query = 'INSERT INTO '.$cm->getCategoriesTableString().' (category_name, friendly_name) VALUES (:title, :friendly_name)';

    $stmt = $con->prepare($query);

    $res = $stmt->execute($items);
    if($res){
      //Now take the user to the preview of post
      return array("success" => 1, "message" => "Category created successfully.", "location" => DashboardLinks::MANAGE_CATEGORIES_VIEW);
    } else{
      //If there was an error, let the user know
      return array("success" => 1, "message" => "Category be created at this time.", DashboardLinks::MANAGE_CATEGORIES_VIEW.$category_id);
    }



  }

}


?>
