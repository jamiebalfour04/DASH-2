<?php

class Get extends DashGet {

  public function getName(){
    return "Get Assets";
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function getData($dashboard){
    $path = $_SERVER['DOCUMENT_ROOT'] . DashHelperFunctions::getTmpLocation().'/*';

    $files = glob($path);


    if(isset($_GET['data'])){
      $post = $dashboard->getContentManager()->fetchPostByName($_GET['data']);
      $files2 = $post->getAllAssets($dashboard);
      $files = array_merge($files, $files2);
    }

    $output = array();
    foreach($files as $file){
      array_push($output, array("name" => basename($file), "path" => $file));
    }


    echo json_encode($output);
  }

}


?>
