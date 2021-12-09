<?php
class DashPushNotificationManager extends DashCoreClass {

  private $app_id = "";
  private $rest_id = "";

  public function __construct($app_id, $rest_id){
    $this->app_id = $app_id;
    $this->rest_id = $rest_id;
  }

  public function sendPushNotification($msgs, $headings, $url=null) {
      if ($this->app_id == "" || $this->rest_id == "") {
          return false;
      }

      $fields = array('app_id' => $this->app_id, 'included_segments' => array('All'), 'headings' => $headings, 'contents' => $msgs);

      if ($url != null) {
          $fields['url'] = $url;
      }

      $fields = json_encode($fields);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic ' . $this->rest_id));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $response = curl_exec($ch);
      curl_close($ch);

      try {
          $return["allresponses"] = $response;
          $return = json_encode($return);
          return $return;
      } catch (Exception $e) {
          return false;
      }
  }
}

?>
