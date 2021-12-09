<?php

class DashContentManager extends DashCoreClass{

  private $dashQuery = null;
  private $con = null;
  private $posts = null;
  private $categories = null;
  private $users = null;
  private $config = array();

  public function __construct(&$conn, $config){
    $this->con = &$conn;
    $this->config = $config;
  }

  public function getAssetsDirectory(){
    return $this->config['assets_directory'];
  }

  public function getTables($id=false){
    if($id !== false){
      if(isset($this->config['database_tables'][$id])){
        return $this->config['database_tables'][$id];
      } else{
        return false;
      }
    }
    return $this->config['database_tables'];
  }

  public function getPostsTableString(){
    return $this->getTables()['POSTS'];
  }

  public function getCategoriesTableString(){
    return $this->getTables()['CATEGORIES'];
  }

  public function getUsersTableString(){
    return $this->getTables()['USERS'];
  }

  public function getNotesTableString(){
    return $this->getTables()['NOTES'];
  }

  public function getCategories($assoc=false){
    //Gets and stores all categories
    if($this->categories == null){
      $this->categories = array();
      if($assoc){
        $this->categories[0] = "General";
      } else{
        $this->categories[0] = new DashCategory(0, "General", "general");
      }

      foreach($this->con->query('SELECT * FROM '.$this->getCategoriesTableString().' ORDER BY category_name ASC') as $cat){
        if($assoc){
          $this->categories[$cat['category_id']] = $cat['category_name'];
        } else{
          $this->categories[$cat['category_id']] = new DashCategory($cat['category_id'], $cat['category_name'], $cat['friendly_name']);
        }

      }
    }
    return $this->categories;
  }

  public function getCategoryFromID($id){
    $this->getCategories();
    return $this->categories[$id];
  }

  public function getCategoryFromName($name){
    $this->getCategories();
    foreach($this->categories as $id => $cat){
      if($cat->getName() == $name){
        return $cat;
      }
    }
    return null;
  }

  public function getCategoryFromFriendlyName($name){
    $this->getCategories();
    foreach($this->categories as $id => $cat){
      if($cat->getFriendlyName() == $name){
        return $cat;
      }
    }
    return null;
  }

  public function getUsers(){
    //Gets and stores all categories
    if($this->users == null){
      $this->users = array();
      $query = "";

      $stmt = $this->con->prepare('SELECT * FROM '.$this->getUsersTableString());

      $stmt->execute(array());
      while($user = $stmt->fetchObject("DashUser")){
        $this->users[$user->getUserID()] = $user;
      }
    }
    return $this->users;
  }

  public function getUserFromID($id){
    $this->getUsers();
    //This is faster than executing another db query
    return $this->users[$id];
  }

  public function getUserFromUsername($username){
    //This has to work by traversing the user list looking for username
    $this->getUsers();
    foreach($this->users as $id => $user){
      if($user->getUsername() == $username){
        return $user;
      }
    }
    return null;
  }

  public function createQueryObject(){
    //Delete a cached search
    $this->posts = null;

    //Create a new query
    $this->dashQuery = new DashSearchQuery($this, $this->con, $this->getPostsTableString());

    return $this->dashQuery;
  }

  public function getQueryObject(){

    //Do not want to return null
    if($this->dashQuery == null){
      $this->dashQuery = new DashSearchQuery($this, $this->con, $this->getPostsTableString());
    }

    return $this->dashQuery;
  }

  private function getPostsNeeded($page_number, $limit){
    //Only return the posts requested
    $out = array();
    if($page_number < 1){
      $page_number = 1;
    }

    $start = ($limit * $page_number) - $limit;
    //This loop takes out the posts required and puts them into the $out array
    for($i = 0; $i < $limit; $i++){
      if($i + $start < count($this->posts)){
        array_push($out, $this->posts[$start + $i]);
      } else{
        return $out;
      }

    }

    return $out;
  }

  public function fetchPostByName($friendlyName){
    $query = 'SELECT * FROM ' . $this->getPostsTableString() . ' WHERE friendly_name = :friendly_name';

    $stmt = $this->con->prepare($query);

    $stmt->execute(array(":friendly_name" => $friendlyName));
    $post = null;
    if($stmt->rowCount() > 0){
      $post = $stmt->fetchObject("DashPost");
      $this->posts = array($post);
    }

    return $post;

  }

  public function fetchPostsForUser($user_id, $page_number, $limit=10, $ascending=false){
    if($ascending){
      $query = 'SELECT * FROM ' . $this->getPostsTableString() . ' WHERE poster = :user_id';
    } else{
      $query = 'SELECT * FROM ' . $this->getPostsTableString() . ' WHERE poster = :user_id ORDER BY date DESC';
    }


    $stmt = $this->con->prepare($query);

    $stmt->execute(array(":user_id" => $user_id));

    $posts = array();
    while($post = $stmt->fetchObject("DashPost")){
      array_push($posts, $post);
    }

    $this->posts = $posts;
    return $this->getPostsNeeded($page_number, $limit);

  }

  public function fetchPosts($page_number, $limit=10, $ascending=false){
    if($this->dashQuery == null){
      $this->createQueryObject();
    }
    //They are cached once it has been run
    if($this->posts != null){
      return $this->getPostsNeeded($page_number, $limit);
    }

    $this->posts = $this->dashQuery->runQuery($ascending);
    return $this->getPostsNeeded($page_number, $limit);
  }

  public function getPostCount(){
    //Returns the total number of posts
    if($this->posts == null){
      return 0;
    }
    return count($this->posts);
  }

  public function autoGenerateQuery(){
    //Generates the query based on the GET parameters provided
    $query = $this->getQueryObject();
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
    //Checking to see if the user is searching for a specific user
    if(isset($_GET['poster'])){
      $query->addUserQuery($_GET['poster']);
    }
  }

  public function autoGeneratePost($post){

    //This is a really simple implementation to generate the values needed for a post
    //This is passed to the parser

    $values = array();
		$values['FRIENDLY_NAME'] = $post->getFriendlyName();
	  $values['POST_TITLE'] = $post->getTitle();
	  $values['POST_DATE'] = $post->getDate();
	  $values['POST_DATE_TEXT'] = '<span class="day">'.date("d", strtotime($post->getDate())).'</span><span class="month">'.date("M", strtotime($post->getDate())).'</span><span class="year">'.date("Y", strtotime($post->getDate())).'</span>';
    $values['POST_POSTER_TEXT'] = $this->getUserFromID($post->getPoster())['username'];
  	$values['POST_CATEGORY_TEXT'] = $this->getCategoryFromID($post->getCategory())->getName();
    $values['POST_CATEGORY'] = $this->getCategoryFromID($post->getCategory())->getName();
	  $values['POST_CONTENT'] = $post->getContent();
    $values['POST_INTRODUCTION'] = htmlentities($post->getIntroduction());
	  $values['POST_TAGS'] = $this->generateTagString($post->getTags());

    return $values;
  }

  public function generateBlogPagination($page_number, $limit, $path, $count=null){
    if($count == null){
      $count = $this->getPostCount();
    }
    $max = ceil($count / $limit);

    $out = '<div class="pages"><ul class="pagination">';
    if ($page_number > 1 && $max > 1) {
        $out .= '<li class="previous"><a href="'.$path.($page_number - 1).'" title="Previous page">&laquo;</a></li>';
    }

    $start = 1;
    $end = $page_number + 4;

    if($page_number > 4){
      $start = $page_number - 4;
    }
    if ($end > $max) {
        $end = $max;
    }

    for ($i = $start; $i <= $end; $i++) {
      $style = "";
      $class = "";
        if ($page_number == $i) {
          $out .= '<li class="active"><a href="'.$path.$i.'">' . $i . '</a></li>';
        } else{
          $out .= '<li><a href="'.$path.$i.'">' . $i . '</a></li>';
        }
    }
    if ($page_number < $end && $end > 1) {
        $out .= '<li class="next"><a href="'.$path.($page_number + 1).'" title="Next page">&raquo;</a></li>';
    }
    $out .= '</ul></div>';

    return $out;
  }

  public function generateTagString($tags){
    $tagString = '<div class="tags">';
		foreach(explode(", ", $tags) as $tag){
			if($tag != ""){
				$tagString .= '<div class="tag"><span class="icon icon-tag2"></span>'.$tag.'</div>';
			}
		}
    $tagString .= '</div>';

    return $tagString;
  }

  public function getPostAssetsPath($post){
    return $this->getAssetsDirectory() . date("Y", strtotime($post->getDate())) . '/' . date("m", strtotime($post->getDate())) . '/' . $post->getFriendlyName();
  }

}
?>
