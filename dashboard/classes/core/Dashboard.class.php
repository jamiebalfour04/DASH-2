<?php

class Dashboard extends DashCoreClass  {

  private $request = null;
  private $dash = null;
  private $datas = array();
  private $plugins = array();

  public const ADMINISTRATOR_ROLE = 0;
  public const EDITOR_ROLE = 1;
  public const WRITER_ROLE = 2;

  function __construct($d, $r, $p){

    $this->dash = $d;
    $this->request = $r;
    $this->plugins = $p;

  }

  public function getConfigOption($opt){
    return $this->dash->getConfigOption($opt);
  }

  public function getInstallationName(){
    return $this->dash->getConfigOption('name');
  }

  //Where to find the content being created
  public function getPublicPath(){
    return $this->dash->getConfigOption('content_location');
  }

  public function getConnection(){
    return $this->dash->getConnection();
  }

  public function getPlugins(){
    return $this->plugins;
  }

  public function getContentManager(){
    return $this->dash->getContentManager();
  }

  public function getRequest(){
    return $this->request;
  }

  public function storeData($name, $data){
    $this->datas[$name] = $data;
  }

  public function getDashboardUser(){
    if(self::isLoggedIntoDash()){
      return unserialize($_SESSION['DASH_LOGIN']['USER']);
    }

    return false;

  }

  public static function isLoggedIntoDash(){
    return isset($_SESSION['DASH_LOGIN']);
  }

}
