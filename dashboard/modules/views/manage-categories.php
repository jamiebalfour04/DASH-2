<?php
class MainView extends DashView{

  public function getName(){
    return "Manage categories";
  }

  public function requiresLogin(){
    return true;
  }

  public function requiresEditorRights(){
    return true;
  }

  public function requiresAdministratorRights(){
    return false;
  }

  public function generateView($dashboard){

    $cm = $dashboard->getContentManager();
    if(!isset($_GET['data'])){
      $page_number = 1;
      if(isset($_GET['page_number'])){
        $page_number = $_GET['page_number'];
      }

      $form = new DashForm();

      $form->setTitle("Manage categories");

      $form->addParagraph("Manage the categories available on this installation.");

      //$form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::DELETE_CONTENT_VIEW.'?page_number='));

      $form->addHtml('<table class="table_flow responsive"><tr><th style="width:200px;">Category name</th><th>&nbsp;&nbsp;</th>');
      $cats = $cm->getCategories();
      foreach($cats as $category_id => $category){
        $form->addHtml('<tr><td>'.$category->getName().'</td>');

        if($category_id != 0){
            $form->addHtml('<td style="text-align:right;width:130px;"><a class="button" href="'.DashboardLinks::MANAGE_CATEGORIES_VIEW.$category_id.'">Rename</a></td></tr>');
        } else{
          $form->addHtml('<td style="text-align:right;width:130px;"></td>');
        }

      }
      $form->addHtml('</table>');

      //Add the navigation
      //$form->addHtml($cm->generateBlogPagination($page_number, 12, DashboardLinks::DELETE_CONTENT_VIEW.'?page_number='));
      $form->addHtml('<div class="center_align"><a class="button" href="'.DashboardLinks::NEW_CATEGORY_VIEW.'">New category</a></div>');
      $form->generate();
      return;
    } else {
      $form = new DashForm(DashboardLinks::MANAGE_CATEGORIES_ACTION);

      $form->setTitle("Category Manager");
      $form->isAjax(true);
      //Find the current category
      $category = $cm->getCategories()[$_GET['data']];

      $form->addHiddenInput("category_id", $_GET['data']);
      $form->addParagraph("You can rename categories from here.");
      $form->addPill("Title", "text", "title", "Category title", "", "", $category->getName());
      $form->addSubmitButton("Save category", "success");

      $form->generate();
    }
  }

}


?>
