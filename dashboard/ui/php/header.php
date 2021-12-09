<?php

	$title = "DASH";

	if($request->getMode() == "view"){
		//Standard, built-in view
		$title = $view->getName();
	} else{
		//Plugin
		$title = $view->getInformation()['name'] . ' plugin';
	}

?>
<!DOCTYPE html>
<html class="<?php if(isset($dashboard_classes)){ echo $dashboard_classes; } ?>" lang="en">
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
		<title>DASH Board | <?php echo $title; ?></title>
  </head>
	<body>
	<div id="main_bar">
    <div id="main_menu_button"><span class="icon-menu glyph"></span></div>
		<a class="item left_icon" id="main_icon" href="https://www.jamiebalfour.scot/projects/wisp/dash/">
			<span class="icon-Dash"></span>
		</a>
		<div id="main_title"> | <?php echo $title; ?></div>
		<div class="item right_item" id="user"><span class="icon-user"></span>
			<ul id="user_options">
    		<li>
    			<a title="Click here to logout of your account." href="<?php echo DashboardLinks::LOGOUT_VIEW; ?>">Logout&nbsp;<span class="icon-log-out glyph"></span></a>
    		</li>
    		<li>
    			<a title="Click here to manage your account." href="<?php echo DashboardLinks::MANAGE_ACCOUNT_VIEW; ?>">Manage your account&nbsp;<span class="icon-v-card glyph"></span></a>
    		</li>
    		<!--<li>
    			<a title="Click here to manage your preferences." href="/dash/view/front/Preferences/">Change your preferences<span class="icon-palette glyph"></span></a>
    		</li>-->
				<?php if($dashboard->getDashboardUser()->isAdministrator()) {?>
    		<li>
					<a href="<?php echo DashboardLinks::SWITCH_USER_VIEW; ?>">Switch user&nbsp;<span class="icon-glyph_icon_switch glyph"></span></a>
				</li>
				<?php } ?>
			</ul>
		</div>

		<span class="xs_hidden xxs_hidden" id="main_opts">
			<a class="item right_item" id="help" href="<?php echo DashboardLinks::HELP_VIEW; ?>">
				<span class="icon-help-with-circle"></span>
			</a>
			<a class="item right_item" href="<?php echo DashboardLinks::DASHBOARD_VIEW; ?>">
				<span class="icon-gauge"></span>
			</a>
			<a class="item right_item" href="<?php echo DashboardLinks::NEW_CONTENT_VIEW; ?>">
				<span class="icon-plus"></span>
			</a>
		</span>

		<span class="xs_hidden xxs_hidden">
			<form id="searchbox" action="<?php echo DashboardLinks::SEARCH_FOR_POST_VIEW; ?>" method="get">
				<div class="input_wrapper">
					<input name="query" placeholder="Search for a post">
				</div>
				<button type="submit"><span class="icon-magnifying-glass glyph"></span></button>
			</form>
		</span>
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
		<div id="menu_bar">
			<?php
				if($dashboard->getPublicPath() !== ""){
					echo '<a id="go_to_site" href="'.$dashboard->getPublicPath().'"><span class="icon-home glyph"></span>Go to site</a>';
				}
			?>
			<div class="inner">
				<?php
					echo DashViewBuilder::generateMenubar($dashboard, null);
				?>
			</div>

		 </div>
		 <div id="main">
			 <div class="inner">
