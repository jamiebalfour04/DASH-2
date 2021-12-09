<?php

class DashForm extends DashCoreClass{

  private $html = "";
  private $parts = array();
  private $link = "";
  private $submit = "";
  private $title = "";
  private $addLogo = false;
  private $centered = false;
  private $ajax = false;

  public function __construct($link=null){
    $this->link = $link;
  }

  public function generate(){

    $use_sections = false;

    $class = "";
    $classes = array();

    if($this->title != ""){
      array_push($classes, 'window');
    }

    if($this->centered == true){
      array_push($classes, 'centered');
    }

    if($this->ajax == true){
      array_push($classes, 'ajax');
    }

    $this->createNewSection();

    if(count($this->parts) > 1){
      $use_sections = true;
    }

    $form_style = "";

    //If using sections, the window.inner will get position absolute and a height of 700px
    if($use_sections){
      array_push($classes, 'has_sections');
    }

    if($this->addLogo){
      array_push($classes, 'has_logo');
    }

    if(count($classes) > 0){
      $class = ' class="';
      foreach($classes as $c){
        $class .= $c . ' ';
      }
      $class .= '"';
    }

    if($this->link != null){
      echo '<form'.$class.$form_style.' method="post" action="'.$this->link.'">';
    } else{
      echo '<form'.$class.$form_style.'>';
    }

    if($this->title != ""){
      echo '<div class="title">'.$this->title.'<div id="date"> | <span></span></div></div>';
    }
    if($this->addLogo){
      echo '<div class="logo_holder"><div class="logo"><span class="icon-Dash glyph"></span></div></div>';
    }

    echo '<div class="window_inner">';
    $pos = 1;
    foreach($this->parts as $part){

      $has_previous = false;
      $has_next = false;

      $previous = "";
      if($pos-1 > 0){
        $previous = ' data-prev-part="part_'.($pos-1).'"';
        $has_previous = true;
      }
      $next = "";
      if(count($this->parts) > $pos){
        $next = ' data-next-part="part_'.($pos+1).'"';
        $has_next = true;
      }

      $style = "";
      if($use_sections){
        $style = 'position:absolute;left:0;width:100%;';
        if($pos > 1){
          $style = 'position:absolute;left:100%;width:100%;';
        }
      }


      echo '<div class="part" id="part_'.$pos.'"'.$previous.$next.' style="'.$style.'">';

      $buttons = "";
      if($has_previous || $has_next){
        $buttons .= '<div class="center_align">';
        if($has_previous){
          $buttons .= '<button class="button prev_part_button">Back</button>';
        }
        if($has_next){
          $buttons .= '<button class="button next_part_button">Next</button>';
        }
        $buttons .= '</div>';
      }


      echo $buttons;

      echo $part;

      echo $buttons;

      if(count($this->parts) == $pos){
        echo '<div class="center_align" style="margin-top:20px">';
        echo $this->submit;
        echo '</div>';
      }

      echo '</div>';
      $pos++;
    }

    echo '</div></form>';
  }

  public function addLogoToForm($v){
    $this->addLogo = $v;
  }

  public function createNewSection(){
    if($this->html != ""){
      array_push($this->parts, $this->html);
      $this->html = "";
    }
  }

  public function isAjax($v){
    $this->ajax = $v;
  }

  public function center($v){
    $this->centered = $v;
  }

  public function addParagraph($c){
    $this->html .= '<p>'.$c.'</p>';
  }

  public function addProgressBar($value, $text, $negative=false){
    $class = "positive";
    if($negative){
      $class = "negative";
    }
    $h = '<div percent="'.$value.'" class="progress_bar '.$class.'"><div class="value"><div class="background"></div><div class="text">'.$text.'</div></div></div>';
    $this->html .= $h;
  }

  public function addHtml($h){
    $this->html .= $h;
  }

  public function setTitle($t){
    $this->title = $t;
  }

  public function addContentPanel($content){
    $this->html .= '<div class="content_panel">'.$content.'</div>';
  }

  public function addButton($text, $id="", $class="", $href=""){
    if($href != ""){
      if($id != ""){
        $this->addHtml('<a href="'.$href.'" id="'.$id.'" class="button '.$class.'">'.$text.'</a>');
      } else{
        $this->addHtml('<a href="'.$href.'" class="button '.$class.'">'.$text.'</a>');
      }
    } else{
      if($id != ""){
        $this->addHtml('<button id="'.$id.'" class="'.$class.'">'.$text.'</button>');
      } else{
        $this->addHtml('<button class="'.$class.'">'.$text.'</button>');
      }
    }

  }

  public function addCenteredButton($text, $id="", $class="", $href=""){
    $this->addHtml('<div class="center_align">');
    $this->addButton($text, $id, $class, $href);
    $this->addHtml('</div>');

  }

  public function addSubmitButton($text="Submit", $class=""){
    $this->submit = '<button type="submit" class="'.$class.'">'.$text.'</button>';
  }

  public function addConfirmButton($text="Submit", $class="", $confirmation="Are you sure you want to do this?"){
    $this->submit = '<button type="submit" class="confirm_btn '.$class.'" data-confirmation="'.$confirmation.'">'.$text.'</button>';
  }

  public function addPill($text, $type, $name, $placeholder="", $id="", $additional="", $value="") {
    if($type == "textarea"){
      $attrs = "";
      if($placeholder != ""){
        $attrs .= ' placeholder="'.$placeholder.'"';
      }
      if($id != ""){
        $attrs .= ' id="'.$id.'"';
      }
      $attrs .= ' ' . $additional;
      $this->html .= '<label class="pill '.$type.'"><span class="pill_name">' . $text . '</span>' . '<textarea name="'.$name.'"'.$attrs.'>' . $value . '</textarea>' . '</label>';
    } else{
      $this->html .= '<label class="pill '.$type.'"><span class="pill_name">' . $text . '</span>' . self::addInput($type, $name, $placeholder, $id, $additional, $value) . '</label>';
    }

  }

  public function addDropdownPill($text, $name, $placeholder="", $id="", $additional="", $items="", $selected=null) {
    $this->html .= '<label class="pill dropdown"><span class="pill_name">' . $text . '</span>';

    //Add space to additional
    if ($additional != "") {
        $additional = " " . $additional;
    }
    //Add ID to additional
    if ($id != "") {
        $additional .= ' id="' . $id . '"';
    }

    $v = '<select class="balfpick" name="'.$name.'"'.$additional.'>';
    if($items != "" && is_array($items)){
      foreach($items as $k => $item){
        if($selected != null && $k == $selected){
          $v .= '<option selected value="'.$k.'">'.$item.'</option>';
        } else{
          $v .= '<option value="'.$k.'">'.$item.'</option>';
        }

      }
    }
    $v .= '</select>';

    $this->html .= $v;

    $this->html .= '</label>';
  }

  public function addPlaceholderInput($text, $type, $name, $id="", $additional="", $value="", $disabled=false) {
      if ($disabled) {
          $this->html .= '<label class="central placeholder disabled"><span>' . $text . '</span>' . self::addInput($type, $name, "", $id, $additional, $value) . '</label>';
      } else {
          $this->html .= '<label class="central placeholder"><span>' . $text . '</span>' . self::addInput($type, $name, "", $id, $additional, $value) . '</label>';
      }
  }

  public function addHiddenInput($name, $value, $id = "") {
      if ($id != "") {
          $id = ' id="' . $id . '"';
      }
      $this->html .= '<input type="hidden" name="' . $name . '"' . $id . ' value="' . $value . '" />';
  }

  public function addContentEditor($content="", $placeholder="Content"){
    $output = "";
    $this->html .= '<div class="mceholder"><div class="central"><textarea placeholder="'.$placeholder.'" name="content" id="contents" aria-hidden="true">'.$content.'</textarea></div></div>';
  }

  private static function addInput($type, $name, $placeholder="", $id="", $additional="", $value="") {
      $v = "";
      //Add space to additional
      if ($additional != "") {
          $additional = " " . $additional;
      }
      //Add ID to additional
      if ($id != "") {
          $additional .= ' id="' . $id . '"';
      }

      if($type == "time"){
        $additional .= ' step="1"';
      }

      switch ($type) {
          case "number":
          case "text":
          case "date":
          case "time":
          case "email":
          case "password":
              if ($value != "") {
                  $value = ' value="' . $value . '"';
              }
              $v = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" ' . $additional . $value . '>';
              break;
          case "radio":
              if ($value != "") {
                  $value = ' value="' . $value . '"';
              }
              $v = '<input type="' . $type . '" name="' . $name . '" ' . $additional . $value . '><span></span>';
              break;
          case "checkbox":
              if ($value != "") {
                  $value = ' value="' . $value . '"';
              }
              $v = '<input type="' . $type . '" name="' . $name . '" ' . $additional . $value . '><span class="icon-check"></span>';
              break;

          case "textarea":
              $v = '<textarea placeholder="' . $placeholder . '" name="' . $name . '"' . $additional . '>' . $value . '</textarea>';
              break;
      }

      return $v;
  }
}

class DashFormTable extends DashCoreClass{

  private $html = "";
  private $class = "";
  private $current_row = "";

  public function addTableClass($class){
    $this->class = $class;
  }

  public function addRow() {

    if($this->current_row != ""){
      $this->html .= $this->current_row . "</tr>";
    }

    $this->current_row = "<tr>";

  }

  public function addColumn($data){
    if($this->current_row == ""){
      $this->current_row = "<tr>";
    }

    $this->current_row = "<td>".$data."</td>";

  }

  public function generate(){
    if($this->current_row != ""){
      $this->html .= $this->current_row . "</tr>";
    }
    return '<table class="'.$this->class.'">'.$this->html.'</table>';
  }

}
