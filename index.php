<?php

//Redirect to the DASH Board


if(isset($_GET['visit_dashboard'])){
  $dashboard = 'dashboard/';
  header("Location: " . $dashboard);
  exit;
}

$qsa = "";

if(count($_GET) > 0){
  $qsa = "?";
}

$pos = 0;
foreach($_GET as $k => $v){
  $qsa .= $k . '=' . $v;
  if($pos + 1 < count($_GET)){
    $qsa .= '&';
  }
  $pos++;
}
header("Location: dashboard/".$qsa);

exit;

?>
