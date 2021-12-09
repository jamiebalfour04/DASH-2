<?php


/*
  This is the DASH Board. This file acts as a router and should not be
  modified.
*/

//Start session if it hasn't already been initiated (https://www.jamiebalfour.scot/projects/dash/development/kb/1)
if(session_status() != PHP_SESSION_ACTIVE){
  session_start();
}

define("DASHBOARD_ROOT_PATH", realpath(dirname(__FILE__)).'/');

include_once DASHBOARD_ROOT_PATH . '/../dash.php';

//Bundle required classes (this method is better than Dash 1.0 because it follows an order and cuts one step (finding the files))
//(https://www.jamiebalfour.scot/projects/dash/development/kb/2)
$requirements = array(
  'core/DashRequest.class.php',
  'core/DashHelperFunctions.class.php',
  'core/DashViewBuilder.class.php',
  'core/Dashboard.class.php',
  'core/DashboardLinks.class.php',
  'core/DashboardUI.class.php',
  'core/DashPushNotificationManager.class.php',
  'objects/DashForm.class.php',
  'interfaces/DashAction.class.php',
  'interfaces/DashGet.class.php',
  'interfaces/DashView.class.php',
  'interfaces/DashPluginInterface.class.php',
);

//Add all required classes
foreach($requirements as $requirement){
  require_once DASHBOARD_ROOT_PATH . '/classes/' . $requirement;
}

?>
