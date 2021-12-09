<?php

class MainView extends DashView{

  private $post = null;

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
    return false;
  }

  public function __construct($dashboard){
    if(isset($_GET['data'])){
      $post_id = $_GET['data'];
      $cm = $dashboard->getContentManager();
      $this->post = $cm->fetchPostByName($post_id);
      if($this->post != null){
        if(!$dashboard->getDashboardUser()->canViewAndAlterPost($this->post)){
          header("Location: " . DashboardLinks::ACCESS_DENIED_VIEW);
          exit;
        }
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
        //Otherwise just find this user's posts
        $posts = $cm->fetchPostsForUser($user->getUserID(), $page_number, 12, false);
      }


      $form = new DashForm(DashboardLinks::EDIT_CONTENT_ACTION);

      $form->setTitle("Edit content");

      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::EDIT_CONTENT_VIEW.'?page_number='));

      $form->addHtml('<table class="table_flow responsive"><tr><th style="width:200px;">Title</th><th>Posted by</th><th>Category</th><th>Status</th><th>Date</th><th>&nbsp;&nbsp;</th>');
      foreach($posts as $post){
        $form->addHtml('<tr><td>'.$post->getTitle().'</td>');
        $form->addHtml('<td>'.$cm->getUserFromID($post->getPoster())->getUsername().'</td>');
        $form->addHtml('<td>'.$cm->getCategoryFromID($post->getCategory())->getName().'</td>');
        $form->addHtml('<td>'.DashHelperFunctions::statusToString($post->getStatus()).'</td>');
        $form->addHtml('<td>'.$post->getDate().'</td>');
        $form->addHtml('<td style="text-align:right;"><a class="button" href="'.DashboardLinks::EDIT_CONTENT_VIEW.$post->getFriendlyName().'">Select</a></td></tr>');

      }
      $form->addHtml('</table>');

      //Add the navigation
      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::EDIT_CONTENT_VIEW.'?page_number='));

      $form->generate();
      return;
    }

    DashHelperFunctions::createTmpLocation();

    $post_id = $_GET['data'];
    $cm = $dashboard->getContentManager();

    if($this->post != null){
      $post = $this->post;

      //Simple process of getting existing assets for post
      $files = $post->getAllAssets($dashboard);
      $assets = array();
      foreach($files as $file){
        $assets[$file] = basename($file);
      }

      $form = new DashForm(DashboardLinks::EDIT_CONTENT_ACTION);
      $form->setTitle("Edit content");
      $form->isAjax(true);
      $form->createNewSection();
      $form->addHiddenInput("post_id", $post_id);
      $form->addPill("Title", "text", "title", "A short title about the content", "", "", $post->getTitle());
      $form->addPill("Introduction", "textarea", "introduction", "An introduction describing the content", "", "", $post->getIntroduction());
      $form->addDropdownPill("Category", "category", "Select a category", "", "", $cm->getCategories(true), $post->getCategory());

      $form->addPill("Date", "date", "date", "Select a date", "date_picker", "", date("Y-m-d", strtotime($post->getDate())));
      $form->addPill("Time", "time", "time", "Select a time", "time_picker", "", date("H:i", strtotime($post->getDate())));
      $form->addPill("Tags (separated by commas)", "textarea", "tags", "Tags describing the content of the post (separated by commas)", "", "", $post->getTags());

      $form->createNewSection();

      //Do replacement of {ASSETS} with actual directory
      $content = str_replace("{ASSETS}", $cm->getPostAssetsPath($post), $post->getContent());
      $form->addContentEditor($content, "Post content");

      $form->createNewSection();
      $form->addHtml("<p>Publishing this post</p>");

      if($post->getStatus() == 2){
        $form->addPill("Publish publicly", "radio", "status", "", "", "checked", "2");
      } else{
        $form->addPill("Publish publicly", "radio", "status", "", "", "", "2");
      }
      if($post->getStatus() == 1){
        $form->addPill("Publish privately", "radio", "status", "", "", "checked", "1");
      } else{
        $form->addPill("Publish privately", "radio", "status", "", "", "", "1");
      }
      if($post->getStatus() == 0){
        $form->addPill("Save and don't publish", "radio", "status", "", "", "checked", "0");
      } else{
        $form->addPill("Save and don't publish", "radio", "status", "", "", "", "0");
      }


      $form->addHtml('<table class="table_pill"><tr><td>');
      $form->addDropdownPill("Banner image", "banner_image", "Post image", "", "banner_img", array_merge(array("" => "None"), $assets), $post->getBannerImage());
      $form->addHtml("</td><td>");
      $form->addButton('<span class="icon icon-glyphicons-82-refresh"></span>', "reload_assets", "reload_button");
      $form->addHtml("</td></tr></table>");

      $form->addSubmitButton("Save changes", "success");

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
