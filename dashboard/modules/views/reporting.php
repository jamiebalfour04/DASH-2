<?php

class MainView extends DashView {

  function apacheModLoaded($s) {
    if (array_search($s, apache_get_modules()) !== false) {
      return "Enabled";
    } else {
      return "Not enabled";
    }
  }

  function phpModLoaded($s)
  {
      if (extension_loaded($s)) {
          return "Yes";
      } else {
          return "No";
      }
  }

  public function getName(){
    return "Reporting";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return true;
  }

  private function generateTable($items){
    echo '<table class="table_flow"><tr><th style="width:200px;">Name</th><th>Value</th>';

    foreach($items as $k => $item){
      echo '<tr><td style="width:200px;">'.$k.'</td><td>'.$item.'</td></tr>';
    }
    echo '</table>';
  }

  public function __construct($dashboard){

  }

  public function generateView($dashboard){

    echo '<h1>DASH dashboard reports and settings</h1>';

    echo '<div class="tab_container">';
    echo '<ul class="tabs"><li><a>Basics</a></li></ul>';
    echo '<div class="tab_content">';
    echo '<h2>Installation basics</h2>';

    $items = array(
      'PHP version' => phpversion(),
      'Host system' => gethostname(),
      'Server document root' => $_SERVER['DOCUMENT_ROOT'],
      'DASH root path' => DASH_PATH,
      'DASH Board path' => DASHBOARD_PATH,
      'DASH Board root path' => DASHBOARD_ROOT_PATH,
      'Install path' => $dashboard->getPublicPath()
    );

    $this->generateTable($items);

    echo '<h2>Connection</h2>';

    $items = array(
      'Host website' => $_SERVER['HTTP_HOST'],
      'Connection IP address' => $_SERVER['REMOTE_ADDR'],
      'SSL/TLS enabled' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "Yes" : "No"
    );

    $this->generateTable($items);

    echo '<h2>User</h2>';

    $items = array(
      'Username' => $dashboard->getDashboardUser()->getUsername(),
      'User type' => DashHelperFunctions::roleToString($dashboard->getDashboardUser()->getRole())
    );

    $this->generateTable($items);


    echo '<h2>Tables</h2>';

    $items = array();
    $tables = $dashboard->getContentManager()->getTables();
    foreach($tables as $k => $table){
      $items[$k] = $table;
    }

    $this->generateTable($items);

    echo '<h2>Apache requirements</h2>';

    $items = array(
      'mod_rewrite' => $this->apacheModLoaded('mod_rewrite'),
      'mod_headers' => $this->apacheModLoaded('mod_headers'),
      'mod_dir' => $this->apacheModLoaded('mod_dir')
    );

    $this->generateTable($items);

    echo '<h2>PHP requirements</h2>';

    $items = array(
      'fileinfo' => $this->phpModLoaded('fileinfo'),
      'json' => $this->phpModLoaded('json'),
      'mbstring' => $this->phpModLoaded('mbstring'),
      'pdo' => $this->phpModLoaded('pdo'),
      'gd' => $this->phpModLoaded('gd')
    );

    $this->generateTable($items);

    echo '</div>';
    echo '</div>';





  }

}


?>
