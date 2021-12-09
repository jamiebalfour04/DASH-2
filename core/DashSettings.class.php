<?php

/*
* A class that stores the settings
*/
class DashSettings extends DashCoreClass
{

  public function parseJSON(string $path){
    $json = json_decode(file_get_contents($path), true);

    foreach($json as $propname => $prop){
      $this->__set($propname, $prop);
    }

  }

}
