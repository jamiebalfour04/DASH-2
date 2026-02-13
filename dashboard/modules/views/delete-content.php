<?php
class MainView extends DashView{

  private $post = null;

  public function getName(){
    return "Delete content";
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
          header("Location: " . DashboardLinks::DELETE_CONTENT_VIEW);
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
        $posts = $cm->fetchPostsForUser($user->getUserId(), $page_number, 12, false);
      }

      $form = new DashForm();

      $form->setTitle("Delete content");

      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::DELETE_CONTENT_VIEW.'?page_number='));

      $form->addHtml('<table class="table_flow responsive"><tr><th style="width:200px;">Title</th><th>Posted by</th><th>Category</th><th>Status</th><th>Date</th><th>&nbsp;&nbsp;</th>');
      foreach($posts as $post){
        $form->addHtml('<tr><td>'.$post->getTitle().'</td>');
        $form->addHtml('<td>'.$cm->getUserFromID($post->getPoster())->getUsername().'</td>');
        $form->addHtml('<td>'.$cm->getCategoryFromID($post->getCategory())->getName().'</td>');
        $form->addHtml('<td>'.DashHelperFunctions::statusToString($post->getStatus()).'</td>');
        $form->addHtml('<td>'.$post->getDate().'</td>');
        $form->addHtml('<td style="text-align:right;"><a class="button" href="'.DashboardLinks::DELETE_CONTENT_VIEW.$post->getFriendlyName().'">Select</a></td></tr>');

      }
      $form->addHtml('</table>');

      //Add the navigation
      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::DELETE_CONTENT_VIEW.'?page_number='));

      $form->generate();
      return;
    }

    $rand = sha1(rand(1, 100));
    $_SESSION['tmp_location'] = $rand;

    $post_id = $_GET['data'];
    $cm = $dashboard->getContentManager();

    $post = $this->post;
    if($this->post != null){

      $form = new DashForm(DashboardLinks::DELETE_CONTENT_ACTION);
      $form->setTitle("Delete content");
      $form->isAjax(true);
      $form->createNewSection();
      $form->addHiddenInput("post_id", $post_id);
      $form->addPill("Title", "text", "title", "", "", "disabled", $post->getTitle());
      $form->addPill("Introduction", "textarea", "introduction", "", "", "disabled", $post->getIntroduction());
      $form->addPill("Category", "text", "category", "", "", "disabled", $cm->getCategoryFromID($post->getCategory())->getName());
      $form->addPill("Banner image", "text", "banner_image", "", "", "disabled", $post->getBannerImage());
      $form->addPill("Date", "text", "", "", "", "disabled", $post->getDate());
      $form->addPill("Tags", "textarea", "", "", "", "disabled", $post->getTags());
      $form->addPill("Classes", "textarea", "", "", "", "disabled", $post->getClasses());

      $form->createNewSection();
      $content = str_replace("{ASSETS}", $cm->getPostAssetsPath($post), strip_tags($post->getContent()));
      $form->addPill("Content", "textarea", "", "", "", 'disabled style="height:350px;"', $content);

      $form->addConfirmButton("Delete this content", "danger", "Are you sure you want to delete this content?");

      echo $form->generate();
    } else{
      $form = new DashForm();
      $form->setTitle("Content not found!");
      $form->addHtml("This content was not found.");
      $form->generate();
    }


  }

}


?>
