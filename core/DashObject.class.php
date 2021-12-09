<?php

/*
* Basic class for interfacing with
*/
class Dash extends DashCoreClass
{

  private $conn = null;
  private $content_manager = null;
  private $config = array();


  function __construct($config){
    include realpath(dirname(__FILE__)).'/../configs.php';

    if($config == "shared_config"){
      exit;
    }

    $this_config = array();
    //Check for a shared_config that means that the features
    if(isset($configs['shared_config'])){
    	foreach($configs['shared_config'] as $key => $value){
        $this_config[$key] = $value;
      }
    }

    foreach($configs[$config] as $key => $value){
      $this_config[$key] = $value;
    }

    $this->config = $this_config;

    $this->content_manager = new DashContentManager($this->conn, $this->config);
    //Now connect to the database
    $this->connect($this->config['database_host'], $this->config['database_name'], $this->config['database_user'], $this->config['database_password']);

    //Checks that the config file has the correct data in it before moving on
    $required = array("name", "dashboard_path", "content_location", "assets_directory", "database_host", "database_user", "database_password", "database_name", "database_tables");
    foreach($required as $r){
      if(!isset($this->config[$r])){
        echo 'CONFIGURATION FILE NOT SETUP CORRECTLY. MISSING DATA FOR ' . $r;
        exit;
      }
    }

    define("DASHBOARD_PATH", $this->config['dashboard_path']);
    define("DASHBOARD_ASSETS_PATH", $this->config['assets_directory']);


    //Checks that the config file has the correct tables before moving on
    $required = array("POSTS", "CATEGORIES", "USERS", "NOTES");
    $tables = $this->config['database_tables'];
    foreach($required as $r){
      if(!isset($tables[$r])){
        echo 'CONFIGURATION FILE NOT SETUP CORRECTLY. MISSING DATA FOR TABLE ' . $r;
        exit;
      }
    }
  }

  public function getConfigOption($opt){
    if(isset($this->config[$opt])){
      return $this->config[$opt];
    }
    return null;
  }

  //Connection to the database, this is kept in the Dash object
  private function connect($host, $database, $username, $password){
    $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password);

    return $this;
  }

  public function getContentManager(){
    return $this->content_manager;
  }

  public function getConnection(){
    return $this->conn;
  }

  public function subscribeEmailClient($email, $name){
    $prefix = $this->content_prefix;
    if($prefix != ""){
      $prefix = $prefix . "_";
    }
    $query = "INSERT INTO " . $prefix . "Followers (email, last_name, first_name) VALUES (:email, :lname, :fname)";

    $stmt = $this->conn->prepare($query);
  }

}
