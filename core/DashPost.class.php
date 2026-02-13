<?php

class DashPost extends DashCoreClass {

  private $friendly_name = "";
  private $post_id = 0;
  private $title = "";
  private $introduction = "";
  private $banner = "";
  private $content = "";
  private $date = "";
  private $category = "";
  private $category_obj = null;
  private $poster = "";
  private $tags = "";
  private $classes = "";
  private $status = 0;



  public function getFriendlyName(){
    return $this->friendly_name;
  }

  public function getPostId(){
    return $this->post_id;
  }

  public function getTitle(){
    return $this->title;
  }

  public function getIntroduction(){
    return $this->introduction;
  }

  public function getBannerImage(){
    return $this->banner;
  }

  public function getContent(){
    return $this->content;
  }

  public function getDate(){
    return $this->date;
  }

  public function getCategory(){
    return $this->category;
  }

  public function getPoster(){
    return $this->poster;
  }

  public function getTags(){
    return $this->tags;
  }

  public function getClasses(){
    return $this->classes;
  }

  public function getStatus(){
    return $this->status;
  }

  public function hasBannerImage(){
    return $this->banner != "";
  }

  public function isHiddenPost(){
    return $this->status == 0;
  }

  public function isUnavailablePost(){
    return $this->status == 1;
  }

  public function isListedPost(){
    return $this->status == 2;
  }

  public function getAllAssets($dashboard){
    //Gets all assets for an existing post
    $path = $_SERVER['DOCUMENT_ROOT'] . $dashboard->getContentManager()->getPostAssetsPath($this) . '/*';
    $files = glob($path);

    foreach($files as $k => $file){
      $files[$k] = str_replace($_SERVER['DOCUMENT_ROOT'], "", $file);
    }
    return $files;

  }

}



?>
