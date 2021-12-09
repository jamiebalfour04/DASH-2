<?php
/*
	This is a demonstration of DASH on the front end
	Feel free to use and modify this code to your own liking
*/
include_once '../dash.php';

//Define these now to be used later on
define("POSTS_PER_PAGE", 10);
define("ASSETS_PATH", '/blog/assets/');
//I would suggest leaving this one since this figures out the root automatically
define("ROOT_BASE_PATH", str_replace($_SERVER['DOCUMENT_ROOT'], "", dirname(__FILE__)));

$page_number = 1;
$qry = "";

//The prefix for this database is Personal_Blog. DASH adds the rest so that it can find the right tables such as Personal_Blog_Posts or Personal_Blog_Categories
$dash = new Dash("blog");

$contentManager = $dash->getContentManager();
$query = $contentManager->createQueryObject();


//Checking to see if the user is searching for posts in a category
if(isset($_GET['qry'])){
	$qry = $_GET['qry'];
  $query->addSearchQuery(htmlentities($_GET['qry']));
}
//Checking to see if the user is searching for posts in a category
if(isset($_GET['category'])){
  $query->addCategoryQuery(htmlentities($_GET['category']));
}
//Checking to see if the user is searching for a specific date
if(isset($_GET['date'])){
  $query->addDateQuery($_GET['date']);
}

//If the user has changed page
if(isset($_GET['page'])){
  $page_number = $_GET['page'];
}

if(!isset($_GET['post'])){
	//If we are not looking at an individual post here
	//All posts or post based on a query
	$posts = $contentManager->fetchPosts($page_number, POSTS_PER_PAGE, true);
	//Read the template
	$template = file_get_contents('templates/blog.html');
} else{
	//The user has requested an individual post
	$post = $contentManager->fetchPostByName($_GET['post'], true);
	//Read the template
	$template = file_get_contents('templates/individual.html');
}



//This section is fairly specific to my website
$page = new Page();
$page->UseMathjax(true);

//If no posts are found
if($contentManager->getPostCount() == 0){
	$page->SetBreadcrumbs(array(ROOT_BASE_PATH => "Blog"));
	$page->PageTitle("<sub>Jamie Balfour's</sub>Articles");
	//On my website I use the GenerateHead function to generate the top part of the website
	$page->GenerateHead();
	//On my website I use the GenerateFoot function to generate the bottom part of the website
	echo 'No posts found that match this criteria.';
	$page->GenerateFoot();
	exit;
}

//Perform all parsing using DASH Boost
$parser = new DashTemplateParser($template);
$template = $parser->parse();

if(!isset($_GET['post'])){
	//If we are not looking at an individual post here
	$page->SetBreadcrumbs(array(ROOT_BASE_PATH => "Dash Demo"));
	$page->PageTitle("<sub>Jamie Balfour's</sub>Personal Blog");
} else{
	//The user has requested an individual post
	$page->SetBreadcrumbs(array(ROOT_BASE_PATH => "Dash Demo", "/posts/".$_GET['post'] => $post->getTitle()));
	$page->PageTitle($post->getTitle());
}

//On my website I use the GenerateHead function to generate the top part of the website
$page->GenerateHead();
//And the GenerateSearch function to generate a search box for my website

if(!isset($_GET['post'])){
	//If we are not looking at an individual post here
  $page_link = ROOT_BASE_PATH .'pages/';
	if(isset($_GET['category'])){
		$page_link = ROOT_BASE_PATH . 'categories/'.$_GET['category'].'/'."pages/";
	}
  if(isset($_GET['qry'])){
		$page_link = ROOT_BASE_PATH . 'search/'.$_GET['qry'].'/'."pages/";
	}
  if(isset($_GET['date'])){
		$page_link = ROOT_BASE_PATH . 'date/'.$_GET['date'].'/'."pages/";
	}
	$pagination = $contentManager->generateBlogPagination($page_number, POSTS_PER_PAGE, $page_link);
  echo $pagination;
  $type = "article_view";
} else{
  $type = "individual_post";
  $posts = array($post);
}

echo '<div class="dash '.$type.'">';

foreach($posts as $post){

	$assets_path = $contentManager->getPostAssetsPath($post);

	$values = array();
	$values['FRIENDLY_NAME'] = $post->getFriendlyName();
  $values['POST_TITLE'] = $post->getTitle();
  $values['POST_LINK'] = ROOT_BASE_PATH.'posts/'.$post->getFriendlyName();
  $values['POST_DATE'] = $post->getDate();
  $values['POST_DATE_LINK'] = ROOT_BASE_PATH.'date/'.date("Y-m-d", strtotime($post->getDate()));
  $values['POST_DATE_TEXT'] = '<span class="day">'.date("d", strtotime($post->getDate())).'</span><span class="month">'.date("M", strtotime($post->getDate())).'</span><span class="year">'.date("Y", strtotime($post->getDate())).'</span>';
	$values['POST_POSTER_TEXT'] = $contentManager->getUserFromID($post->getPoster())->getUsername();
	$values['POST_CATEGORY_TEXT'] = $contentManager->getCategoryFromID($post->getCategory())->getName();
	$values['POST_CATEGORY'] = $contentManager->getCategoryFromID($post->getCategory())->getName();
	$values['POST_CATEGORY_LINK'] = ROOT_BASE_PATH.'categories/'.urlencode($contentManager->getCategoryFromID($post->getCategory())->getFriendlyName());
  $values['POST_CONTENT'] = str_replace("{ASSETS}", $assets_path, $post->getContent());
	$values['POST_INTRODUCTION'] = htmlentities($post->getIntroduction());
  $values['POST_TAGS'] = $contentManager->generateTagString($post->getTags());
  $values['IS_ADMIN'] = isJamieBalfour();
  $values['IS_HIDDEN_POST'] = $post->isHiddenPost();
  $values['IS_UNAVAILABLE_POST'] = $post->isUnavailablePost();
  $values['IS_LISTED_POST'] = $post->isListedPost();

	if($post->hasBannerImage()){
		$values['POST_IMAGE'] = str_replace("{ASSETS}", $assets_path, $post->getBannerImage());
	}

  echo $parser->traverseAST($template, $values);

}

echo '</div>';


if(!isset($_GET['post'])){
  echo $pagination;
}

//On my website I use the GenerateFoot function to generate the bottom part of the website
$page->GenerateFoot();
?>
