<?php

class MainView extends DashView{

  private $note = null;

  public function getName(){
    return "Delete note";
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

  public function __construct($dashboard){
    if(isset($_GET['data'])){
      $con = $dashboard->getConnection();
      $note_id = $_GET['data'];
      $user = $dashboard->getDashboardUser();

      $query = 'SELECT * FROM '.$cm->getNotesTableString() . ' WHERE user_id = :user_id AND note_id = :note_id';

      $stmt = $con->prepare($query);
      $res = $stmt->execute(array(":user_id" => $user->getUserID(), ":note_id" => $note_id));


      if($res){
        if($stmt->rowCount() == 0){
          header("Location: " . DashboardLinks::ACCESS_DENIED_VIEW);
          exit;
        }

        $this->note = $stmt->fetch(PDO::FETCH_ASSOC);
      }

    } else{
      $form = new DashForm();
      $form->setTitle("Note not found!");
      $form->addHtml("This content was not found.");
      $form->generate();
    }

  }

  public function generateView($dashboard){
    $note_id = $_GET['data'];
    $note = $this->note;

    $form = new DashForm(DashboardLinks::DELETE_NOTE_ACTION);
    $form->setTitle("Delete note");
    $form->isAjax(true);
    $form->addHiddenInput("note_id", $note_id);

    $form->addPill("Title", "text", "title", "A short title about the content", "", "disabled", $note['title']);
    $form->addPill("Content", "textarea",  "", "", "", 'disabled style="min-height:300px"', strip_tags($note['content']));

    $form->addConfirmButton("Delete note", "danger", "Are you sure you want to delete this note?");

    $form->generate();


  }

}


?>
