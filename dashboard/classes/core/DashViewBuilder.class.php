<?php

class DashViewBuilder {
    //Basic reusable function to generate a pill label

    public static function BoolToCheckBox($value) {
        $checked = "";
        if ($value == "true" || $value == "1" || $value == "on") {
            $checked = ' checked="checked"';
        }
        return $checked;
    }

    private static function generateItem($is_title, $text, $link, $is_active, $has_glyph = true) {
        $output = "";
        $classes = '';
        if ($is_active) {
            $classes .= 'active"';
        }
        if ($classes != '') {
            $classes = ' class="' . $classes . '"';
        }

        if ($is_title) {
            $output .= '<li class="title">' . $text . '<span class="icon-chevron-small-right"></span></li>';
        } else {
            $class = "";
            if (!$has_glyph) {
                $class = ' class="no_glyph"';
            }
            $output .= '<li' . $class . '><a href="' . $link . '"' . $classes . '>' . $text . '</a></li>';
        }

        return $output;
    }

    private static function generateGroup($request, $title, $items){
      //This will generate the sidebar groups automatically

      $is_open = false;
      $item_string = "";
      foreach($items as $item){
        $current = false;
        if(DASHBOARD_PATH.'view/'.$request->getPage().'/' == $item['link']){
          $is_open = true;
          $current = true;
        }

        if(isset($item['additional_links'])){
          foreach($item['additional_links'] as $l){
            if(DASHBOARD_PATH.'view/'.$request->getPage().'/' == $l){
              $is_open = true;
              $current = true;
            }
          }
        }

        $item_string .= self::generateItem(false, $item['text'], $item['link'], $current);
      }

      if($is_open){
        echo '<ul class="item_group open">';
      } else{
        echo '<ul class="item_group">';
      }

      echo self::generateItem(true, $title, '', false);
      if($is_open){
        echo '<ul class="items">';
      } else{
        echo '<ul class="items" style="display:none;">';

      }
      echo $item_string;
      echo '</ul>';
      echo '</ul>';

    }

    public static function generateMenubar($dashboard, $current_user=null) {
        //Double check that there is someone logged in
        $request = $dashboard->getRequest();
        if ($current_user != null) {
            $admin_user_change = $request->getPage() == DashboardLinks::CHANGE_USER && isset($_POST['user']);
        }



        $items = array();
        array_push($items, array("text" => '<span class="icon-gauge glyph"></span>DASH Board', "link" => DashboardLinks::DASHBOARD_VIEW));
        array_push($items, array("text" => '<span class="icon-v-card glyph"></span>Manage account', "link" => DashboardLinks::MANAGE_ACCOUNT_VIEW));
        self::generateGroup($request, "Home", $items);
        $items = array();
        array_push($items, array("text" => '<span class="icon-new-message glyph"></span>New content', "link" => DashboardLinks::NEW_CONTENT_VIEW));
        array_push($items, array("text" => '<span class="icon-pencil glyph"></span>Edit content', "link" => DashboardLinks::EDIT_CONTENT_VIEW));
        array_push($items, array("text" => '<span class="icon-trash glyph"></span>Delete content', "link" => DashboardLinks::DELETE_CONTENT_VIEW));
        array_push($items, array("text" => '<span class="icon-magnifying-glass glyph"></span>Preview content', "link" => DashboardLinks::PREVIEW_CONTENT_VIEW));
        array_push($items, array("text" => '<span class="icon-clipboard glyph"></span>Your notes', "link" => DashboardLinks::MANAGE_NOTES_VIEW, "additional_links" => array(DashboardLinks::EDIT_NOTE_VIEW)));
        self::generateGroup($request, "Content", $items);

        if($dashboard->isLoggedIntoDash() && ($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
          $items = array();
          array_push($items, array("text" => '<span class="icon-colours glyph"></span>Manage categories', "link" => DashboardLinks::MANAGE_CATEGORIES_VIEW));
          if($dashboard->isLoggedIntoDash() && $dashboard->getDashboardUser()->isAdministrator()){

            array_push($items, array("text" => '<span class="icon-line-graph glyph"></span>Reporting', "link" => DashboardLinks::REPORTING_VIEW));
            array_push($items, array("text" => '<span class="icon-users glyph"></span>Manage users', "link" => DashboardLinks::MANAGE_USERS_VIEW));
            array_push($items, array("text" => '<span class="icon-export glyph"></span>Export content', "link" => DashboardLinks::EXPORT_VIEW));
            array_push($items, array("text" => '<span class="icon-glyph_icon_switch glyph"></span>Switch User', "link" => DashboardLinks::SWITCH_USER_VIEW));

          }
          self::generateGroup($request, "Administration", $items);
        }

        $items = array();
        foreach($dashboard->getPlugins() as $plugin){
          $add = true;
          if($plugin->requiresEditorRights() && !($dashboard->getDashboardUser()->isAdministrator() || $dashboard->getDashboardUser()->isEditor())){
            $add = false;
          }
          if($plugin->requiresAdministratorRights() && (!$dashboard->getDashboardUser()->isAdministrator())){
            $add = false;
          }

          if($add){
            array_push($items, array("text" => '<span class="icon-plus glyph"></span>'.$plugin->getInformation()['name'], "link" => DashboardLinks::PLUGIN_VIEW.$plugin->getHash()));
          }
        }


        if(count($items) > 0){
          self::generateGroup($request, "Plugins", $items);
        }

        $items = array();
        array_push($items, array("text" => '<span class="icon-help-with-circle glyph"></span>Help', "link" => DashboardLinks::HELP_VIEW));
        array_push($items, array("text" => '<span class="icon-check glyph"></span>Provide feedback', "link" => 'https://www.jamiebalfour.scot/contact?subject=dash'));
        self::generateGroup($request, "Information", $items);

    }
}

?>
