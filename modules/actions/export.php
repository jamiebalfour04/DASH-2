<?php

function exportDatabase($con, $tables=false, $backup_name=false) {

  $output = "";
  $query = "SET NAMES 'utf8'";
  $con->query($query);



  $queryTables = $con->query('SHOW TABLES');
  foreach ($queryTables as $row) {
    $target_tables[] = $row[0];
  }

  if($tables !== false) {
    $target_tables = array_intersect($target_tables, $tables);
  }

  foreach ($target_tables as $table) {
    $query = 'SELECT * FROM ' . $table;
    $stmt = $con->prepare($query);

    $result = $stmt->execute(array());

    $fields_amount = $stmt->columnCount();
    $rows_num = $stmt->rowCount();

    $stmt2 = $con->prepare('SHOW CREATE TABLE ' . $table);

    $stmt2->execute();

    $res = $stmt2->fetch(PDO::FETCH_ASSOC);

    $output .= "DROP TABLE IF EXISTS `".$table."`;\n\n";

    $output .= $res['Create Table'] . ";\n\n";

    //$content = (!isset($content) ? '' : $content) . "\n\n" . $res[1] . ";\n\n";
    $content = "";
    for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
      while ($row = $stmt->fetch()) {//when started (and every after 100 command cycle):
        if ($st_counter % 100 == 0 || $st_counter == 0) {
          $content .= "\nINSERT INTO " . $table . " VALUES";
        }
        $content .= "\n(";
        for ($j = 0; $j < $fields_amount; $j++) {
          $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
          if (isset($row[$j])) {
            $content .= '"' . $row[$j] . '"';
          } else {
            $content .= '""';
          }
          if ($j < ($fields_amount - 1)) {
            $content .= ',';
          }
        }
        $content .= ")";
        //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle earlier
        if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
          $content .= ";";
        } else {
          $content .= ",";
        }
        $st_counter = $st_counter + 1;
      }
      $content .= "\n\n\n";
    }

    $output .= $content;

  }

  file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . DASHBOARD_PATH . '/tmp/database_export.sql.download.temp', $output);
  return array("success" => 1, "message" => "Content created successfully.", "location" => DASHBOARD_PATH . 'tmp/database_export.sql.download.temp');
}

class MainAction extends DashAction {

  public function getName(){
    return "Edit content";
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
    $cm = $dashboard->getContentManager();

    $tables = array();
    $count = 0;

    if(isset($_POST['posts'])){
      array_push($tables, $cm->getTables('POSTS'));
      $count++;
    }
    if(isset($_POST['categories'])){
      array_push($tables, $cm->getTables('CATEGORIES'));
      $count++;
    }
    if(isset($_POST['users'])){
      array_push($tables, $cm->getTables('USERS'));
      $count++;
    }
    if(isset($_POST['notes'])){
      array_push($tables, $cm->getTables('NOTES'));
      $count++;
    }

    return exportDatabase($con, $tables);

  }

}


?>
