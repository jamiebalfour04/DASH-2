<?php
include_once DASHBOARD_ROOT_PATH . '/login/login_functions.php';
class MainAction extends DashAction {

  public function getName(){
    return "Switch User";
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

  public function performAction($dashboard){

    $con = $dashboard->getConnection();

    $query = "SELECT * FROM ".$dashboard->getContentManager()->getUsersTableString() . " WHERE user_id=:user_id AND role != 0";

    $stmt = $con->prepare($query);

    $result = $stmt->execute(array(":user_id" => $_POST['user_id']));

    if($result){
      if($stmt->rowCount() > 0){
        $config_name = $_SESSION['DASH_LOGIN']['CONFIG'];
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        loginUser($config_name, $user);
      }

    }

  }

}


?>
