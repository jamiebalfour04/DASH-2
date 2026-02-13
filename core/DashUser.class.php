<?php

  class DashUser extends DashCoreClass{

    private $user_id = -1;
    private $email = "";
    private $username = "";
    private $role = 0;
    private $display_name = "";


    //Directly construct from database
    public function setData($user_id, $username, $email, $display_name, $role){
      $this->user_id = $user_id;
      $this->username = $username;
      $this->email = $email;
      $this->display_name = $display_name;
      $this->role = $role;
    }

    public function getUserID(){
      return $this->user_id;
    }

    public function getUsername(){
      return $this->username;
    }

    public function getDisplayName(){
      if ($this->display_name == "") {
        return $this->username;
      } else{
        return $this->display_name;
      }
    }

    public function getEmailAddress(){
      return $this->email;
    }

    public function getRole(){
      return $this->role;
    }

    public function isAdministrator(){
      return $this->role == Dashboard::ADMINISTRATOR_ROLE;
    }

    public function isEditor(){
      return $this->role == Dashboard::EDITOR_ROLE;
    }

    public function getPostCount($dashboard){
      $cm = $dashboard->getContentManager();

      $query = 'SELECT COUNT(*) FROM ' . $cm->getPostsTableString() . ' WHERE poster = "' . $this->getUserID() .'"';
      $conn = $dashboard->getConnection();

      foreach($conn->query($query) as $row){
        return $row['COUNT(*)'];
      }
    }

    public function getNoteCount($dashboard){
      $cm = $dashboard->getContentManager();

      $query = 'SELECT COUNT(*) FROM ' . $cm->getNotesTableString() . ' WHERE user_id = "' . $this->getUserID() .'"';
      $conn = $dashboard->getConnection();

      foreach($conn->query($query) as $row){
        return $row['COUNT(*)'];
      }
    }

    public function canViewAndAlterPost($post){

      if($this->isAdministrator() || $this->isEditor()){
        return true;
      }

      if($post->getPoster() == $this->getUserId()){
        return true;
      }

      return false;
    }
  }

?>
