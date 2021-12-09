<?php
class MainView extends DashView{

  public function getName(){
    return "Search results";
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
    $user = $dashboard->getDashboardUser();
?>
    <h1>Search results</h1>
    <?php

      $page_number = 1;
      if(isset($_GET['page_number'])){
        $page_number = $_GET['page_number'];
      }

      if(isset($_GET['data'])){
        $page_number = $_GET['data'];
      }

      $cm = $dashboard->getContentManager();
      $query = $cm->getQueryObject();
      $query->addSearchQuery($_GET['query']);
      $posts = $cm->fetchPosts($page_number, 12);

      $form = new DashForm();
      $form->setTitle("DASH Search");
      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::SEARCH_FOR_POST_VIEW.'?query='.$_GET['query'].'&page_number='));
      if(count($posts) > 0){
        $form->addHtml('<ul class="big_list">');
        foreach($posts as $post){

          $content = $post->getIntroduction();
          if($content == ""){
            $content = strip_tags($post->getContent());
            if(strlen($content) > 100){
              $content = substr($content, 0, 100) . '...';
            }

          }

          $form->addHtml('<li><a href="'.DashboardLinks::PREVIEW_CONTENT_VIEW.$post->getFriendlyName().'"><div class="title">'.$post->getTitle().'</div><div class="content">'.$content.'</div></a></li>');
        }
        $form->addHtml('</ul>');
      } else{
        $form->addHtml('<p class="center_align">There are no posts available using this search criteria.</p>');
      }

      $form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::SEARCH_FOR_POST_VIEW.'?query='.$_GET['query'].'&page_number='));
      $form->generate();
    ?>

<?php
  }
}
?>
