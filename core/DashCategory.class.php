<?php

class DashCategory extends DashCoreClass{

  private $id = -1;
  private $name = "";
  private $friendly_name = "";

  public function __construct($id, $n, $fn){
    $this->id = $id;
    $this->name = $n;
    $this->friendly_name = $fn;
  }

  public function getID(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }

  public function getFriendlyName(){
    return $this->friendly_name;
  }

}
