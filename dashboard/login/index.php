<?php
//This page must be separate from the main dashboard
include '../../configs.php';

$has_multiple_configs = false;
if(count($configs) > 1){
	$has_multiple_configs = true;
} else{
	$config_id = array_keys($configs)[0];
}
if(isset($_GET['config'])){
	$has_multiple_configs = false;
	$config_id = $_GET['config'];
	if(!isset($configs[$config_id])){
		exit;
	}
}
define("DASHBOARD_PATH", '../');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="theme-color" content="<?php //echo $theme_color; ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="<?php echo DASHBOARD_PATH.'ui/fonts/icomoon/style.css'; ?>" rel="stylesheet">
		<link href="<?php echo DASHBOARD_PATH.'ui/css/dashboard.unminified.css'; ?>" rel="stylesheet">
    <link href="<?php echo DASHBOARD_PATH.'ui/css/girder.css'; ?>" rel="stylesheet">
    <link href="<?php echo DASHBOARD_PATH.'ui/css/balfpick.css'; ?>" rel="stylesheet">
		<script>
			var Dash = new Object();
			Dash.DashboardPath = "<?php echo DASHBOARD_PATH; ?>";
		</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<title>DASH Board | Login</title>
		<style>
			#container{
				background-size:cover;
				background-position:center;
				background-image:url('https://www.jamiebalfour.scot/projects/dash/images/random.php');
			}
			.login_main{
				position:absolute;
				left:0px;
				right:0px;
				top:50px;
				bottom:0px;
				overflow:hidden;
			}
			.login_switch{
				position:absolute;
				top:0;
				left:0;
				bottom:0;
				width:100%;
				color:#000;
				color:var(--dashboard-color);
				padding: 10px;
			}
		</style>
  </head>
  <body>
  	<div id="main_bar">
      <div id="main_menu_button"><span class="icon-menu glyph"></span></div>
  		<a class="item left_icon" id="main_icon" href="https://www.jamiebalfour.scot/projects/wisp/dash/">
  			<span class="icon-Dash"></span>
  		</a>
  		<div id="main_title"> | Login</div>
  		<a class="item right_item" id="help" href="https://www.jamiebalfour.scot/projects/dash/">
  			<span class="icon-help-with-circle glyph"></span>
      </a>
  	</div>
		<div id="dialog_wrapper">
			<div id="dialog">
				<div id="dialog_inner">
					<div id="dialog_title"></div>
					<div id="dialog_content"></div>
					<div id="dialog_close_wrapper">
						<div id="dialog_close"><span class="icon-circle-with-cross"></span></div>
					</div>
				</div>
			</div>
		</div>
    <div id="container">
      <div id="login_screen">
        <form class="window has_logo" method="post" id="login_form" action="login.php">
          <div class="title">&nbsp;</div>
          <div class="logo_holder"><div class="logo"><span class="icon-Dash glyph"></span></div></div>
					<div class="login_main">
						<?php if(isset($_GET['url'])) { echo '<input type="hidden" name="url" value="'.$_GET['url'].'">'; } ?>

						<?php if($has_multiple_configs){?>
						<div class="login_switch" id="part1">
							<p>Pick the appropriate DASH installation you want to work with.</p>
							<label class="pill dropdown"><span class="pill_name">DASH install</span>
								<select name="config" class="balfpick">
									<?php
											foreach($configs as $config_id => $config){
												if($config_id != "shared_config"){
													if(!(isset($config['hidden']) && $config['hidden'] == true)){
														echo '<option value="'.$config_id.'">'.$config['name'].'</option>';
													}
												}
											}
									?>
								</select>
							</label>
							<div class="center_align">
		            <a class="button" id="next">Next</a>
		          </div>
						</div>
						<?php
							} else{
									echo '<input name="config" type="hidden" value="'.$config_id.'">';
							}
						?>
						<div class="login_switch" <?php if ($has_multiple_configs){echo 'style="left:100%;"';}?> id="part2">
		          <label class="pill">
		            <span class="pill_name">Username</span>
		            <input name="username" placeholder="Username">
		          </label>
		          <label class="pill">
		            <span class="pill_name">Password</span>
		            <input name="password" type="password" placeholder="Password">
		          </label>
		          <div class="center_align">
		            <button id="submit" type="submit">Login</button>
		          </div>
						</div>

					</div>
        </form>
      </div>
    </div>
    <script src="<?php echo DASHBOARD_PATH.'ui/js/balfpick.js'; ?>"></script>
    <script src="<?php echo DASHBOARD_PATH.'ui/js/script.js'; ?>"></script>
    <script src="<?php echo DASHBOARD_PATH.'ui/js/forms.js'; ?>"></script>
    <script>
      $('select.balfpick').BalfPick();
			$("#next").on("click", function(e){
				e.preventDefault();
				$("#part1").animate({"left" : "-100%"});
				$("#part2").animate({"left" : "0"});
			})
			$("#submit").on("click", function(e){
				e.preventDefault();
				$("#submit").prop("disabled", true);
				$.ajax({
					url : "login.php?json=true",
					method : "post",
					data : $("#login_form").serialize(),
					success : function(d){
						d = $.parseJSON(d);
						$("#submit").prop("disabled", false);
						if(d.result == 1){
							Dash.showAlert("Success", "You have been logged in successfully.", function(){
								if(d.location != undefined){
									window.location = "<?php echo DASHBOARD_PATH; ?>" + d.location;
								} else{
									window.location = "<?php echo DASHBOARD_PATH; ?>";
								}
							});


						} else{
							Dash.showAlert("Failure", "Incorrect login details. Ensure you have picked the correct content management system.");
						}
					}
				})
			})

    </script>
  </body>
</html>
