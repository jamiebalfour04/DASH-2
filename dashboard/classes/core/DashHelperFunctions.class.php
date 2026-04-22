<?php

class DashHelperFunctions {
  //Strips Emojis so that they aren't inserted into the friendly name of the post
  private static function removeEmojis($string) {

    // Remove most emoji + symbols outside basic multilingual plane

    return preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $string);

  }

  //Creates a URL friendly name for the post.
  public static function generateFriendlyNameOld($title) {
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

  public static function generateFriendlyName($title) {
    if ($title === null) {
        return '';
    }

    $friendly_name = (string) $title;

    // Decode any HTML entities first
    $friendly_name = html_entity_decode($friendly_name, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Remove emojis and other supplementary-plane characters
    $friendly_name = self::removeEmojis($friendly_name);

    // Replace slashes with hyphens before stripping characters
    $friendly_name = str_replace('/', '-', $friendly_name);

    // Strip HTML tags just in case
    $friendly_name = strip_tags($friendly_name);

    // Transliterate accented and non-ASCII letters where possible
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $friendly_name);
    if ($transliterated !== false) {
        $friendly_name = $transliterated;
    }

    // Lowercase
    $friendly_name = strtolower($friendly_name);

    // Replace apostrophes rather than leaving odd fragments
    $friendly_name = str_replace(array("'", "’", '"'), '', $friendly_name);

    // Replace any non letter/number with a hyphen
    $friendly_name = preg_replace('/[^a-z0-9]+/', '-', $friendly_name);

    // Collapse repeated hyphens
    $friendly_name = preg_replace('/-+/', '-', $friendly_name);

    // Trim hyphens from both ends
    $friendly_name = trim($friendly_name, '-');

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
