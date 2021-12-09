<?php

//The very first file required does the configuration
include_once 'dashboard.php';


define("VIEWS_PATH",   DASHBOARD_ROOT_PATH . '/modules/views/');
define("ACTIONS_PATH", DASHBOARD_ROOT_PATH . '/modules/actions/');
define("GET_PATH",     DASHBOARD_ROOT_PATH . '/modules/get/');
define("PLUGINS_PATH", DASHBOARD_ROOT_PATH . '/plugins/');


if(isset($_GET['tmp'])){
  if(!Dashboard::isLoggedIntoDash()){
    //Display an error that the file cannot be accessed
    echo 'You cannot access this file.';
  } else{
    //Load the image
    $f = dirname(realpath(__FILE__)).'/tmp/'.$_GET['tmp'];
    if(file_exists($f)){
      header("Content-type: ".mime_content_type($f));
      if(strpos($f, ".download") !== 0){
        //Strips all of the . command like thing from file name
        $name = "";
        $i = 0;
        $count = 0;
        $base = basename($f);
        while($i < strlen($base) && $count < 2){
          $name .= $base[$i];
          if($base[$i] == "."){
            $count++;
          }
          $i++;
        }
        //Sets the file to download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$name);
      }
      readfile($f);
      if(strpos($f, ".temp") !== 0){
        //Deletes the file after opening or downloading
        unlink($f);
      }
    }
  }

  //Whenever returning the file, simply exit after finding it or not to avoid extra stuff being added (and potentially wasting system resources)
  exit;
}

if(isset($_SESSION['DASH_LOGIN'])){
  $cnf = $_SESSION['DASH_LOGIN']['CONFIG'];
} else{
  //If the user was trying to access a specific part of the DASH installation,
  //pass this to the login form to redirect them back there on successfully logging in
  $login_url = "";
  if(isset($_GET['mode'])){
    $login_url = $_GET['mode'] . '/';
  }
  if(isset($_GET['page'])){
    $login_url .= $_GET['page'] . '/';
  }
  if(isset($_GET['data'])){
    $login_url .= $_GET['data'] . '/';
  }

  if($login_url == ""){
    $login_url = "view/home/";
  }
  $uri = $_SERVER['REQUEST_URI'];

  while(!DashHelperFunctions::endsWith($uri, "/dashboard/")){
    $uri = substr($uri, 0, strlen($uri) - 1);
  }

  if(isset($_GET['config'])){

    header("Location: ".$uri."login/?config=".$_GET['config'] . '&url='.$login_url);
  } else{
    header("Location: ".$uri."login/" . '?url='.$login_url);
  }

  exit;
}

//define("ASSETS_DIRECTORY", $current_config['assets_directory']);



$dash = new Dash($cnf);

if (!isset($_GET['mode'])) {
    $mode = "view";
} else {
    $mode = $_GET['mode'];
}

if (!isset($_GET['page'])) {
    $page = "home";
    header("Location: ".DashboardLinks::DASHBOARD_VIEW);
    exit;
} else {
    $page = $_GET['page'];
}

if (!isset($_GET['data'])) {
    $data = null;
} else {
    $data = $_GET['data'];
}

//The request is built up of the GET parameters are helps us define the route
$request = new DashRequest($mode, $page, $data);

//Get plugins
$plugins = array();
foreach(glob(PLUGINS_PATH . '/*') as $plugin){
  $f = basename($plugin);
  include $plugin . '/' . $f . '.plugin.php';
  $plugin = 'Plugin';
  $plugin = new $plugin();
  $plugins[$plugin->getHash()] = $plugin;
}

$dashboard = new Dashboard($dash, $request, $plugins);
$current_user = $dashboard->getDashboardUser();

//Mode determines whether this part is a view or an action
if($mode == "view" || $mode == "plugin_view"){

  $can_be_performed = true;
  $view = null;

  if($mode == "view"){
    $path = VIEWS_PATH . $page . '.php';
    $view = 'MainView';
    include $path;
    if(file_exists($path)){
      //Get the view from it's class which should be the route (with an uppercase) and the word View
      $view = new $view($dashboard);
    } else {
      $can_be_performed = false;
      header("Location: ".DashboardLinks::ACCESS_DENIED_VIEW);
      exit;
    }
  } else{
    $view = $plugins[$page];
  }
  //Get the view




  if($view != null){
    //Check if the view requires the user to be logged in
    if($view->requiresLogin() && !Dashboard::isLoggedIntoDash()){
      $can_be_performed = false;
    }
    //Check if the view requires the user to be at least an editor
    if($view->requiresEditorRights() && !($current_user->isEditor() || $current_user->isAdministrator())){
      $can_be_performed = false;
    }
    //Check if the view requires the user to be an admin
    if($view->requiresAdministratorRights() && !$current_user->isAdministrator()){
      $can_be_performed = false;
    }
  }
  if($can_be_performed){
    $ui = new DashboardUI();
    //Generate the content of the view with the head and foot appended
    $ui->generateUI($dashboard, $request, $view);
  } else{
    header("Location: ".DashboardLinks::ACCESS_DENIED_VIEW);
    exit;
  }


} else if ($mode == "get") {
  //GET requests can be made that allow access to data using AJAX such as all assets
  if(file_exists(GET_PATH . $page . '.php')){
    include GET_PATH . $page . '.php';
    $get = 'Get';
    $get = new $get();
    $can_be_performed = true;

    if($get->requiresEditorRights() && !($current_user->isEditor() || $current_user->isAdministrator())){
      $can_be_performed = false;
    }
    //Check if the action requires the user to be an admin
    if($get->requiresAdministratorRights() && !$current_user->isAdministrator()){
      $can_be_performed = false;
    }

    if($can_be_performed){
      echo $get->getData($dashboard);
    }
    exit;
  }
} else if ($mode == "action"){
  if(file_exists(ACTIONS_PATH . $page . '.php')){
    //Get the action from it's class which should be the route
    include ACTIONS_PATH . $page . '.php';
    $action = 'MainAction';
    $action = new $action();
    $can_be_performed = true;

    //Check if the action requires the user to be logged in
    if($action->requiresLogin() && !Dashboard::isLoggedIntoDash()){
      $can_be_performed = false;
    }
    if($action->requiresEditorRights() && !($current_user->isEditor() || $current_user->isAdministrator())){
      $can_be_performed = false;
    }
    //Check if the action requires the user to be an admin
    if($action->requiresAdministratorRights() && !$current_user->isAdministrator()){
      $can_be_performed = false;
    }
    if($can_be_performed){
      //Reply using the standard DASH response, which checks if the request was done using AJAX
      $result = $action->performAction($dashboard);
      if(isset($result['location'])){
        $location = $result['location'];
      } else{
        $location = DashboardLinks::DASHBOARD_VIEW;
      }
      if($result['success'] == 1) {
        //Success
        DashHelperFunctions::respond(1, $location, $result['message']);
      } else {
        //Failure
        DashHelperFunctions::respond(0, $location, $result['message']);
      }
    } else{
      DashHelperFunctions::respond(0, DashboardLinks::ACCESS_DENIED_VIEW, $result['message']);
    }

  } else {
    header("Location: ".DashboardLinks::ACCESS_DENIED_VIEW);
    exit;
  }
}


?>
