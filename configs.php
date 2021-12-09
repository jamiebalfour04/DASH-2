<?php

/*
* Feel free to add your own logic into this file. For example, use an include
* to add some database connection info OR use an includes to include a separate file.
* For example if you had a config file in the root of your website called myconfig.php,
* simply copy or uncomment the code below to use that instead.
*
* include $_SERVER['DOCUMENT_ROOT'] . '/myconfig.php';
*/

/*

TEMPLATE CONFIGURATION

$configs = array(
  "blogs" => array(
    "name"                  => "Personal Blog",
    "content_location"      => "/blog/",
    "assets_directory"      => "/blog/assets/",
    "dashboard_path"        => "/dash/dashboard/",
    "database_host"         => "localhost",
    "database_user"         => "jamesbond007",
    "database_password"     => "007",
    "database_name"         => "blogs",
    "database_tables"       => array(
        "POSTS"             => "Blog_Posts",
        "CATEGORIES"        => "Blog_Categories",
        "USERS"             => "Blog_Users",
        "NOTES"             => "Blog_User_Notes"
    )
  )
);

*/

include $_SERVER['DOCUMENT_ROOT'] . '/assets/misc/dash_configs.php';


?>
