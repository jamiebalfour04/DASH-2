<?php

include 'dashboard.php';
$cnf = $_SESSION['DASH_LOGIN']['CONFIG'];
$dash = new Dash($cnf);
$cm = $dash->getContentManager();

if(!isset($_SESSION['DASH_LOGIN'])){
  exit;
}

//Upload to DASH tmp folder for now
$path = $_SERVER['DOCUMENT_ROOT'] . DashHelperFunctions::getTmpLocation().'/';

//We need to create any folders needed
if(!file_exists($path)){
  mkdir($path, 0777, true);
}

$target_file = $path.urlencode($_FILES["file"]["name"]);
//echo $target_file;
if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){

  //The outfile is still in tmp
  $out_file = str_replace($_SERVER['DOCUMENT_ROOT'], "", $target_file);
  $res = array("location" => $out_file);

  //Lastly send back a JSON response
  echo json_encode($res);
} else{

  //The outfile is still in tmp
  $res = array("location" => "Failure");

  //Lastly send back a JSON response
  echo json_encode($res);
}

?>
