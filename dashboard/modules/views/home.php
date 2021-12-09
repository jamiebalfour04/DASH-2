<?php
class MainView extends DashView{

  public function getName(){
    return "DASH Board";
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
    <h1>DASH Home<sub><?php echo $dashboard->getInstallationName(); ?></sub></h1>

    <div class="group left"><div class="title">Dashboard<div id="date"> | <span></span></div></div>
      <p style="font-size:110%;margin:10px;">
        Welcome to your DASH Board, <?php echo $user->getUsername(); ?>. From here, you can manage
        everything on this DASH installation, create, edit and delete content and much more.
      </p>
      <div class="row">
        <div class="col_lg_6 col_md_5">
          <div class="large_group">
            <div class="title">
              Your information
            </div>
            <div class="content">
              <div style="background:linear-gradient(90deg, #29a1a1, #08e3e3, #29a1a1);" class="widget">
                <div>You have</div>
                <div class="value"><?php echo strtolower(DashHelperFunctions::roleToString($user->getRole())); ?></div>
                <div>privileges</div>
              </div>
              <div style="background:linear-gradient(90deg, #0f9b1a, #00f513, #0f9b1a);" class="widget">
                <div>You connected from</div>
                <div class="value"><?php echo $_SERVER['REMOTE_ADDR']; ?></div>
                <div>(your IP address)</div>
              </div>
              <div style="background:linear-gradient(90deg, #fa3c4c, #fd8d96, #fa3c4c);" class="widget">
                <div>You've posted</div>
                <div class="value"><?php echo $user->getPostCount($dashboard); ?></div>
                <div>times</div>
              </div>
              <div style="background:linear-gradient(90deg, #3cabfa, #a9d4f3, #3cabfa);" class="widget">
                <div>You've created</div>
                <div class="value"><?php echo $user->getNoteCount($dashboard); ?></div>
                <div>notes</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col_lg_6 col_md_7">
          <div class="large_group" id="front_notes">
            <div class="title">
              Quick note
            </div>
            <div class="content">
              <?php
                $form = new DashForm(DashboardLinks::NEW_NOTE_ACTION);
                $form->addPill("Title", "text", "title", "A short title describing this note");
                $form->addContentEditor("", "Create a quick note here");
                $form->addSubmitButton("Save note");
                $form->generate();
              ?>
            </div>
          </div>
        </div>
      </div>
      <?php
      $cm = $dashboard->getContentManager();
      $posts = $cm->fetchPostsForUser($user->getUserId(), 1, 6, false);

      ?>
      <div class="large_group">
        <div class="title">Recent posts</div>
        <div class="row">
          <?php
          foreach($posts as $post){
          ?>
          <div class="col_lg_4 col_md_4 col_sm_6">
            <a href="<?php echo DashboardLinks::PREVIEW_CONTENT_VIEW.'/'.$post->getFriendlyName();?>" class="front_post">
              <div class="title">
                <?php echo $post->getTitle();?>
              </div>
              <div class="intro">
                <?php echo $post->getIntroduction();?>
              </div>
            </a>
          </div>
          <?php
          }
          ?>

        </div>
      </div>
    </div>

<?php
  }
}
?>
