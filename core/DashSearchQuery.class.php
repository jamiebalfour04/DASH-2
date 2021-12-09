<?php

class DashSearchQuery extends DashCoreClass{

  private $contentManager = null;
  private $con = null;
  private $main_query = 'SELECT * FROM ';
  private $searchQuery = '';
  private $categoryQuery = '';
  private $dateQuery = '';
  private $userQuery = '';
  private $queryValues = array();
  private $showUnavailablePosts = false;
  private $showHiddenPosts = false;

  public function __construct(&$owner, &$conn, $table){
    $this->con = &$conn;
    $this->contentManager = &$owner;
    $this->main_query .= $table;
  }

  private static function generateSearchArray($query){
    $inQuote = false;
    $arr = array();
    $current = "";
    $i = 0;
    //Iterate the whole query
    while ($i < strlen($query)) {
      $ch = $query[$i];
      //If $ch is a quote, we are inside a quote next
      if ($ch == '"') {
        $inQuote = !$inQuote;
        if (!$inQuote) {
          array_push($arr, $current);
          $current = "";
        }
        //We only care for spaces inside quote marks
      } elseif ($ch == " " && !$inQuote) {
        //If we meet a space and the $current word is not nothing, then put it in to the array
        if ($current != "") {
          array_push($arr, $current);
          $current = "";
        }
      } else {
        //Add the current character to the current word
        $current .= $ch;
      }

      $i++;
      //Push the last word in to the array
      if ($i == strlen($query) && $current != "") {
        array_push($arr, $current);
      }
    }

    return $arr;
  }

  public function addSearchQuery($search){
    //$this->searchQuery = ' content LIKE CONCAT("%", :query, "%") OR introduction LIKE CONCAT("%", :query, "%")';
    $this->searchQuery = '(content RLIKE :query OR title RLIKE :query OR tags RLIKE :query)';

    $queryArray = self::generateSearchArray($search);

    $out = "";
    $pos = 0;

    foreach ($queryArray as $search) {
        //Regex escape pipe | characters
        if ($pos != 0) {
            $out .= "|";
        }
        $out .= $search;
        $pos++;
    }

    $this->queryValues[':query'] = $out;
  }

  public function addCategoryQuery($search){
    //Reverse search, we want the category_id now
    $id = $this->contentManager->getCategoryFromFriendlyName($search)->getID();
    $this->categoryQuery = 'category = :category';
    $this->queryValues[':category'] = $id;
  }

  public function addDateQuery($search){
    //Date search
    $this->dateQuery = 'date LIKE CONCAT(:date, "%")';
    $this->queryValues[':date'] = $search;
  }

  public function addUserQuery($search){
    //Reverse search, we want the category_id now
    $id = $this->contentManager->getUserFromUsername($search)['user_id'];
    $this->userQuery = 'poster = :poster_id';
    $this->queryValues[':poster_id'] = $id;
  }

  public function showUnavailablePosts($v){
    $this->showUnavailablePosts = $v;
  }

  public function showHiddenPosts($v){
    $this->showHiddenPosts = $v;
  }

  private function addQuery($original_query, $new_query){
    if($new_query != ""){
      if($original_query != ""){
        return $original_query . ' AND ' . $new_query;
      } else{
        return $new_query;
      }
    }
    return $original_query;
  }

  private function getQueryString($ascending){
    $query = $this->main_query;

    $newSearch = "";

    //Create the query
    $newSearch = $this->addQuery($newSearch, $this->searchQuery);
    $newSearch = $this->addQuery($newSearch, $this->categoryQuery);
    $newSearch = $this->addQuery($newSearch, $this->dateQuery);
    $newSearch = $this->addQuery($newSearch, $this->userQuery);

    if($this->showUnavailablePosts == false){
      if($newSearch != ""){
        $newSearch .= ' AND ';
      }
      $newSearch .= 'status != 1';
    }

    if($this->showHiddenPosts == false){
      if($newSearch != ""){
        $newSearch .= ' AND ';
      }
      $newSearch .= 'status != 0';
    }

    if($newSearch != ""){
      $query .= ' WHERE ' . $newSearch;
    }

    if($ascending){
      return $query . ' ORDER BY date ASC';
    } else{
      return $query . ' ORDER BY date DESC';
    }

  }

  public function runQuery($ascending=false){

    $query = $this->getQueryString($ascending);
    $values = $this->queryValues;

    $stmt = $this->con->prepare($query);
    $stmt->execute($values);

    $output = array();

    while($row = $stmt->fetchObject("DashPost")){
      array_push($output, $row);
    }


    return $output;
  }

}


?>
