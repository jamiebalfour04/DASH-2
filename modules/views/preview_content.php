<?php

class MainView extends DashView{

  private $post = null;

  public function getName(){
    return "Preview content";
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
      $post_id = $_GET['data'];
      $cm = $dashboard->getContentManager();
      $this->post = $cm->fetchPostByName($post_id);

      if(!$dashboard->getDashboardUser()->canViewAndAlterPost($this->post)){
        header("Location: " . DashboardLinks::PREVIEW_CONTENT_VIEW);
        exit;
      }
    }

  }

  public function generateView($dashboard){
    if(!isset($_GET['data'])){
      $page_number = 1;
      if(isset($_GET['page_number'])){
        $page_number = $_GET['page_number'];
      }

      $user = $dashboard->getDashboardUser();

      $cm = $dashboard->getContentManager();

      $cm->getQueryObject()->showHiddenPosts(true);
      $cm->getQueryObject()->showUnavailablePosts(true);

      if($user->isAdministrator() || $user->isEditor()){
        //These types of users can access all posts
        $posts = $cm->fetchPosts($page_number, 12, false);
      } else{
        $posts = $cm->fetchPostsForUser($user->getUserId(), $page_number, 12, false);
      }

      $form = new DashForm();

      $form->setTitle("Preview content");



      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::PREVIEW_CONTENT_VIEW.'?page_number='));

      $form->addHtml('<table class="table_flow responsive"><tr><th style="width:200px;">Title</th><th>Posted by</th><th>Category</th><th>Status</th><th>Date</th><th>&nbsp;&nbsp;</th>');
      foreach($posts as $post){
        $form->addHtml('<tr><td>'.$post->getTitle().'</td>');
        $form->addHtml('<td>'.$cm->getUserFromID($post->getPoster())->getUsername().'</td>');
        $form->addHtml('<td>'.$cm->getCategoryFromID($post->getCategory())->getName().'</td>');
        $form->addHtml('<td>'.DashHelperFunctions::statusToString($post->getStatus()).'</td>');
        $form->addHtml('<td>'.$post->getDate().'</td>');
        $form->addHtml('<td style="text-align:right;"><a class="button" href="'.DashboardLinks::PREVIEW_CONTENT_VIEW.$post->getFriendlyName().'">Select</a></td></tr>');

      }
      $form->addHtml('</table>');

      //Add the navigation
      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::PREVIEW_CONTENT_VIEW.'?page_number='));

      $form->generate();
      return;
    }

    $post_id = $_GET['data'];
    $cm = $dashboard->getContentManager();



    if($this->post != null){
      $post = $this->post;
      $form = new DashForm();
      $form->setTitle($post->getTitle());
      $form->addHtml('<div id="preview_area">');
      $form->addHtml('<div style="font-style:italic;">Posted in ' . $cm->getCategoryFromID($post->getCategory())->getName() . ' by ' . $cm->getUserFromID($post->getPoster())->getUsername() . ' on ' . date("Y-m-d", strtotime($post->getDate())) . '</div>');
      $form->addHtml('<div style="margin:20px 0;font-size:130%;">'.$post->getIntroduction() .'</div>');


      $content = str_replace("{ASSETS}", $cm->getPostAssetsPath($post), $post->getContent());
      $form->addHtml($content);

      $form->addHtml('<div class="post_tags">' . $cm->generateTagString($post->getTags()) .'</div>');

      $form->addHtml('<div class="center_align"><a href="'.DashboardLinks::EDIT_CONTENT_VIEW.$post_id.'" class="button">Edit content</a><a href="'.DashboardLinks::DELETE_CONTENT_VIEW.$post_id.'" class="button danger">Delete content</a></div>');
      $form->addHtml('</div>');


      $form->generate();
    } else{
      $form = new DashForm();
      $form->setTitle("Content not found!");
      $form->addHtml("This content was not found.");
      $form->generate();
    }






  }

}


?>
