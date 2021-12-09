<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

define("DASH_PATH", realpath(dirname(__FILE__)));


//Bundle required classes (this method is better than DASH 1.0 because it follows an order and cuts one step (finding the files))
$requirements = array(
  'DashCoreClass.class.php',
  'DashSettings.class.php',
  'DashSearchQuery.class.php',
  'DashContentManager.class.php',
  'DashTemplateParser.class.php',
  'DashCategory.class.php',
  'DashObject.class.php',
  'DashUser.class.php',
  'DashPost.class.php'
);

//Add all required classes
foreach($requirements as $requirement){
  require_once realpath(dirname(__FILE__)).'/' . 'core/' . $requirement;
}


?>
