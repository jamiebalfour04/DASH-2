<?php

class DashHelperFunctions {
  //Strips Emojis so that they aren't inserted into the friendly name of the post
  private static function removeEmojis($text) {
      //http://stackoverflow.com/questions/35961245/how-to-remove-all-emoji-from-string-php
      $regex = '/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u';
      return preg_replace($regex, '', $text);
  }

  //Creates a URL friendly name for the post.
  public static function generateFriendlyName($title) {
      $friendly_name = self::removeEmojis($title);

      $friendly_name = trim($friendly_name, " ");
      $friendly_name = str_replace("/", "-", $friendly_name);

      $friendly_name = str_replace("&39;", "", $friendly_name);
      $friendly_name = html_entity_decode(urldecode($friendly_name));

      $friendly_name = self::removeUnsavouryCharacters($friendly_name, array("@", "!", "?", "#", "$", "%", "^", "&", "'", '"', "’", ",", "[", "]", "(", ")", "*", ":", ";", "."));


      $friendly_name = strip_tags($friendly_name);
      $friendly_name = str_replace("39%3B", "", $friendly_name);

      $friendly_name = strtolower(str_replace(" ", "-", $friendly_name));
      $friendly_name = urlencode($friendly_name);

      return $friendly_name;
  }

  //Removes a list of bad characters from a string
  public static function removeUnsavouryCharacters($string, $chars) {
      $out = $string;
      foreach ($chars as $char) {
          $out = str_replace($char, "", $out);
      }
      return $out;
  }

  public static function roleToString($role){
    if($role == 0){
      return "Administrator";
    } else if($role == 1){
      return "Editor";
    } else{
      return "Writer";
    }
  }

  public static function statusToString($status){
    if($status == 2){
      return "Public";
    } else if($status == 1){
      return "Private";
    } else{
      return "Not visible";
    }
  }

  public static function respond($result, $location, $msg = ""){
    if(isset($_GET['response']) && $_GET['response'] == "json"){
      //JSON style response, e.g. for AJAX calls
      $vals = array("response" => $result, "message" => $msg, "location" => $location);
      echo json_encode($vals);
      exit;
    } else{
      header("Location: ".$location);
      exit;
    }
  }

  public static function removeDuplicateSlashes($str){
    return preg_replace('#/+#','/',$str);
  }

  public static function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  public static function endsWith($haystack, $needle){
    $length = strlen($needle);
    return $length === 0 || (substr($haystack, -$length) === $needle);
  }

  public static function createTmpLocation(){
    //Create a random directory in the tmp location
    $rand = sha1(rand(1, 1000));
    while(file_exists(DASHBOARD_ASSETS_PATH.'/tmp/'.$rand)){
      $rand = sha1(rand(1, 1000));
    }

    $_SESSION['DASH_LOGIN']['TEMPORARY_PATH'] = $rand;
  }

  public static function getTmpLocation(){

    if(isset($_SESSION['DASH_LOGIN']) && isset($_SESSION['DASH_LOGIN']['TEMPORARY_PATH'])){
      $tmp_path = DASHBOARD_ASSETS_PATH . '/tmp/' . $_SESSION['DASH_LOGIN']['TEMPORARY_PATH'];
      $tmp_path = self::removeDuplicateSlashes($tmp_path);

      return $tmp_path;
    }


    return false;
  }

  public static function prepareSQL($query, $values){
    foreach($values as $k => $value){
      $query = str_replace($k, $value, $query);
    }

    return $query;
  }
}
