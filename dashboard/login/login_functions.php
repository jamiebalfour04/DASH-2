<?php

include_once realpath(dirname(__FILE__).'/../../').'/core/DashCoreClass.class.php';
include_once realpath(dirname(__FILE__).'/../../').'/core/DashUser.class.php';

/*
*  This file can be included in any other file to login a user, provided the correct
*  details are provided.
*/

function loginUser($config_name, $user){
  $user_obj = new DashUser();
  $user_obj->setData(
    $user['user_id'],
    $user['username'],
    $user['email'],
    $user['display_name'],
    $user['role']
  );
  $_SESSION['DASH_LOGIN'] = array("CONFIG" => $config_name, "USER" => serialize($user_obj));
  return true;
}

?>
