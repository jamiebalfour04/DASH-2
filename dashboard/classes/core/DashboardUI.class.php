<?php

class DashboardUI extends DashCoreClass  {

  private $dashboard = null;
  private $request = null;
  private $view = null;

  public function generateUI($dashboard, $request, $view){

    $this->dashboard = $dashboard;
    $this->request = $request;
    $this->view = $view;

    include DASHBOARD_ROOT_PATH . 'ui/php/header.php';
    $view->generateView($dashboard);
    include DASHBOARD_ROOT_PATH . 'ui/php/footer.php';
  }

}


?>
