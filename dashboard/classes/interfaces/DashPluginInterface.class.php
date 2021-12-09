<?php


  //Create the basic concrete functions that all plugins have
  class DashPluginBase extends DashCoreClass {
    public $configuration;
    public $path;

    public function __construct(){
      //Constructor will be used to log info about plugin
    }

    //A unique hash for this class
    public function getHash(){
      return sha1(__CLASS__);
    }

    public function getConfigurationOption($v){
      if(isset($this->configuration[$v])){
        return $this->configuration[$v];
      } else {
        return null;
      }
    }
  }

  //All plugins should implement this interface
  abstract class DashPlugin extends DashPluginBase
  {
      abstract protected function getInformation();
      abstract protected function generateView();
      abstract protected function performAction();
      abstract protected function showOnMenu();
      abstract protected function getMenuString();
      abstract protected function pluginMenuIcon();
      abstract protected function pluginDarkMenuIcon();
      abstract protected function requiresLogin();
      abstract protected function requiresEditorRights();
      abstract protected function requiresAdministratorRights();

  }
