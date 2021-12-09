<?php

include '../../configs.php';
include_once 'login_functions.php';


//Function to ensure correct data has been supplied
function getData($config, $data){
  if(isset($config[$data])){
    return $config[$data];
  } else{
    echo '<p>CONFIG FILE NOT CONFIGURED CORRECTLY. MISSING DATA FOR '.$data.'.</p>';
    exit;
  }

}

$location = "";
if(isset($_POST['url'])){
  $location = $_POST['url'];
}

//print_r($configs);
unset($_SESSION['DASH_LOGIN']);
$config_id = $_POST['config'];

$config = array();

if(isset($configs['shared_config'])){
  foreach($configs['shared_config'] as $key => $value){
    $config[$key] = $value;
  }
}

if(isset($configs[$config_id])){
  foreach($configs[$config_id] as $key => $value){
    $config[$key] = $value;
  }
}

$host = getData($config, "database_host");
$database = getData($config, "database_name");
$username = getData($config, "database_user");
$password = getData($config, "database_password");
$table = getData(getData($config, "database_tables"), "USERS");

$conn = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);

$query = "SELECT * FROM ".$table." WHERE username=:username";

$stmt = $conn->prepare($query);

$res = $stmt->execute(array(":username" => $_POST['username']));

if($res){
  if($stmt->rowCount() > 0){
    //Success, a user has been found
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $success = false;

    if($user['password_salted'] == 1){
      if(password_verify($_POST['password'], $user['password'])){
        $success = true;
      }
    } else{
      if($_POST['password'] == $user['password']){
        $success = true;
      }
    }


    if($success){
      loginUser($_POST['config'], $user);
      if(isset($_GET['json'])){
        echo json_encode(array("result" => 1, "location" => $location));
        exit;
      } else{
        header("Location: " . DASHBOARD_PATH);
        exit;
      }

    }

  }

}
if(isset($_GET['json'])){
  echo json_encode(array("result" => 0));
  exit;
} else{
  header("Location: " . DASHBOARD_PATH . '/login/?failure=true');
  exit;
}


?>
