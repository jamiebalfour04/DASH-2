<?php

header("Content-Type: text/css");

if(isset($_GET['file'])){
  echo '/* '.$_GET['file'].' */' . "\r\n";
  if(file_exists($_SERVER['DOCUMENT_ROOT'] . $_GET['file'])){
    //echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . $_GET['file']);
  }
}

echo '/* Editor CSS */' . "\r\n";

echo file_get_contents('editor.css') . "\r\n";


?>
