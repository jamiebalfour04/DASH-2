<?php

class MainView extends DashView{

  private $note = null;

  public function getName(){
    return "Your notes";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return false;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function generateView($dashboard){
    if(!isset($_GET['data'])){
      $page_number = 1;
      if(isset($_GET['page_number'])){
        $page_number = $_GET['page_number'];
      }

      $user = $dashboard->getDashboardUser();

      $con = $dashboard->getConnection();

      $query = 'SELECT * FROM '.$dashboard->getContentManager()->getNotesTableString() . ' WHERE user_id = :user_id';
      $stmt = $con->prepare($query);
      $res = $stmt->execute(array(":user_id" => $user->getUserID()));

      if(!$res){

      }

      $form = new DashForm();

      $form->setTitle("Your notes");

      $form->addHtml($dashboard->getContentManager()->generateBlogPagination($page_number, 8, DashboardLinks::EDIT_CONTENT_VIEW.'?page_number=', $stmt->rowCount()));

      $form->addHtml('<table class="table_flow responsive"><tr><th>Title</th><th>Modified</th><th>&nbsp;&nbsp;</th>');
      $pos = 0;
      //Where to stop finding posts from
      $pn = ($page_number * 8);
      while($note = $stmt->fetch(PDO::FETCH_ASSOC)){

        if($pos >= $pn - 8 && $pos < $pn){
          $form->addHtml('<tr>');
          $form->addHtml('<td>'.$note['title'].'</td>');
          $form->addHtml('<td>'.$note['modified'].'</td>');
          $form->addHtml('<td style="text-align:right;">');
          $form->addHtml('<a class="button" href="'.DashboardLinks::MANAGE_NOTES_VIEW.$note['note_id'].'">View</a>');
          $form->addHtml('<a class="button" href="'.DashboardLinks::EDIT_NOTE_VIEW.$note['note_id'].'">Edit</a>');
          $form->addHtml('<a class="button danger" href="'.DashboardLinks::DELETE_NOTE_VIEW.$note['note_id'].'">Delete</a></td>');
          $form->addHtml('</tr>');
        }

        $pos++;
      }
      $form->addHtml('</table>');

      $form->addHtml('<div class="center_align"><a class="button" href="'.DashboardLinks::NEW_NOTE_VIEW.'">New note</a></div>');

      //Add the navigation
      $form->addHtml($dashboard->getContentManager()->generateBlogPagination($page_number, 8, DashboardLinks::MANAGE_NOTES_VIEW.'?page_number=', $stmt->rowCount()));

      $form->generate();
      return;
    }

    $note_id = $_GET['data'];

    $con = $dashboard->getConnection();
    $user = $dashboard->getDashboardUser();

    $query = 'SELECT * FROM '.$dashboard->getContentManager()->getNotesTableString() . ' WHERE user_id = :user_id AND note_id = :note_id';

    $stmt = $con->prepare($query);
    $res = $stmt->execute(array(":user_id" => $user->getUserID(), ":note_id" => $note_id));

    if($stmt->rowCount() == 0){
      $form = new DashForm();
      $form->setTitle("Content not found!");
      $form->addHtml("This note was not found.");
      $form->generate();
      return;
    }

    $note = $stmt->fetch(PDO::FETCH_ASSOC);

    $form = new DashForm();

    $form->setTitle($note['title']);

    $mod = "";
    if($note['time'] != $note['modified']){
      $mod = ' / Last modified: ' . $note['modified'];
    }

    $form->addParagraph('<strong>Created ' . $note['time'] . $mod .'</strong>');
    $form->addContentPanel($note['content']);

    $form->addHtml('<div class="center_align" style="margin-top:20px;">');
    $form->addHtml('<a class="button" href="'.DashboardLinks::EDIT_NOTE_VIEW.$note_id.'">Edit</a>');
    $form->addHtml('<a class="button danger" href="'.DashboardLinks::DELETE_NOTE_VIEW.$note_id.'">Delete</a>');
    $form->addHtml('</div>');

    $form->generate();

  }

}


?>
