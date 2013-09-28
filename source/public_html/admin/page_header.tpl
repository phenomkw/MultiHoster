<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-us" xml:lang="en-us">
<head>
	<!--
		Charisma v1.0.0

		Copyright 2012 Muhammad Usman
		Licensed under the Apache License v2.0
		http://www.apache.org/licenses/LICENSE-2.0

		http://usman.it
		http://twitter.com/halalit_usman
	-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta http-equiv="imagetoolbar" content="no" />
    
    <title>Administration Control Panel</title>

	<!-- The styles -->
	<link id="bs-css" href="css/bootstrap-simplex.css" rel="stylesheet">
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/charisma-app.css" rel="stylesheet">
	<link href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href="css/fullcalendar.css" rel="stylesheet">
	<link href="css/fullcalendar.print.css" rel="stylesheet"  media='print'>
	<link href="css/chosen.css" rel="stylesheet">
	<link href="css/uniform.default.css" rel="stylesheet">
	<link href="css/colorbox.css" rel="stylesheet">
	<link href="css/jquery.cleditor.css" rel="stylesheet">
	<link href="css/jquery.noty.css" rel="stylesheet">
	<link href="css/noty_theme_default.css" rel="stylesheet">
	<link href="css/elfinder.min.css" rel="stylesheet">
	<link href="css/elfinder.theme.css" rel="stylesheet">
	<link href="css/jquery.iphone.toggle.css" rel="stylesheet">
	<link href="css/opa-icons.css" rel="stylesheet">
	<link href="css/uploadify.css" rel="stylesheet">

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
    <link rel="shortcut icon" href="css/images/favicon.ico" />

	<!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" />
	<![endif]-->

	<!-- jQuery -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

	<script type="text/javascript" src="http://gettopup.com/releases/latest/top_up-min.js"></script>
	<script type="text/javascript" src="source/includes/scripts/base64_min.js"></script>
	<script type="text/javascript" src="source/public_html/admin/adminjscript.js"></script> 
	<script type="text/javascript" src="source/includes/scripts/img_preview.js"></script>

	<!-- external javascript
	================================================== -->

	<!-- jQuery UI -->
	<script src="source/includes/scripts/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="source/includes/scripts/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="source/includes/scripts/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="source/includes/scripts/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="source/includes/scripts/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="source/includes/scripts/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="source/includes/scripts/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="source/includes/scripts/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="source/includes/scripts/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="source/includes/scripts/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="source/includes/scripts/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="source/includes/scripts/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="source/includes/scripts/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="source/includes/scripts/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="source/includes/scripts/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src="source/includes/scripts/fullcalendar.min.js"></script>
	<!-- data table plugin -->
	<script src="source/includes/scripts/jquery.dataTables.min.js"></script>

	<!-- chart libraries start -->
	<script src="source/includes/scripts/excanvas.js"></script>
	<script src="source/includes/scripts/jquery.flot.min.js"></script>
	<script src="source/includes/scripts/jquery.flot.pie.min.js"></script>
	<script src="source/includes/scripts/jquery.flot.stack.js"></script>
	<script src="source/includes/scripts/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="source/includes/scripts/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<script src="source/includes/scripts/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="source/includes/scripts/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="source/includes/scripts/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="source/includes/scripts/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="source/includes/scripts/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="source/includes/scripts/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="source/includes/scripts/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="source/includes/scripts/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="source/includes/scripts/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="source/includes/scripts/jquery.history.js"></script>
	<!-- application script for Charisma demo -->
	<script src="source/includes/scripts/charisma.js"></script>

</head>

<body>
		<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="admin.php"> <img alt="" src="img/logo20.png" /> <span>MultiHoster ACP</span></a>

				<!-- theme selector starts -->
				<div class="btn-group pull-right theme-container" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="">
						<i class="icon-eye-open"></i><span class="hidden-phone"> Change Theme</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" id="themes">
						<li><a data-value="classic" href="#"><i class="icon-blank"></i> Classic</a></li>
						<li><a data-value="cerulean" href="#"><i class="icon-blank"></i> Cerulean</a></li>
						<li><a data-value="cyborg" href="#"><i class="icon-blank"></i> Cyborg</a></li>
						<li><a data-value="redy" href="#"><i class="icon-blank"></i> Redy</a></li>
						<li><a data-value="journal" href="#"><i class="icon-blank"></i> Journal</a></li>
						<li><a data-value="simplex" href="#"><i class="icon-blank"></i> Simplex</a></li>
						<li><a data-value="slate" href="#"><i class="icon-blank"></i> Slate</a></li>
						<li><a data-value="spacelab" href="#"><i class="icon-blank"></i> Spacelab</a></li>
						<li><a data-value="united" href="#"><i class="icon-blank"></i> United</a></li>
					</ul>
				</div>
				<!-- theme selector ends -->
				
			</div>
		</div>
   <!-- Here's all it takes to make this navigation bar. -->
<div class="top-nav">
   <ul id="nav_menu">
			<li><a href="index.php">Site Index</a></li>
			<li><a href="admin.php">Dashboard</a></li>
			<li><a href="admin.php?act=images">Images</a></li>
			<li><a href="admin.php?act=site_settings">Site Settings</a></li>
			<li><a href="admin.php?act=language_settings">Language Settings</a></li>
			<li><a href="admin.php?act=mass_email">Bulk E-Mail</a></li>
<li class="dropdown">
<a id="drop1" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">User Managment <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
			<li><a href="admin.php?act=user_list">Members List</a></li>
			<li><a href="admin.php?act=ban_control">Ban Control</a></li>
</ul>
</li>
<li class="dropdown">
<a id="drop2" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Logs <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">
			<li><a href="admin.php?act=file_logs">File Logs</a></li>
			<li><a href="admin.php?act=robot_logs">Search Engine Logs</a></li>
			<li><a href="admin.php?act=errlogs">PHP Error Logs</a></li>
</ul>
</li>
<li class="dropdown">
<a id="drop3" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">Debug Info <b class="caret"></b></a>
<ul class="dropdown-menu" role="menu" aria-labelledby="drop3">
			<li><a href="admin.php?act=phpinfo">PHP Information</a></li>
      <li><a href="admin.php?act=processes">Running Processes</a></li>
      <li><a href="admin.php?act=sysinfo">System Information</a></li>
</ul>
</li>
   </ul>
</div>
   <!-- That's it! -->
	</div>
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">

			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet">Menu</li>
						<li><a href="index.php"><i class="icon-home"></i><span class="hidden-tablet"> Site Index</span></a></li>
						<li><a href="admin.php"><i class="icon-book"></i><span class="hidden-tablet"> Dashboard</span></a></li>
						<li><a href="admin.php?act=images"><i class="icon-picture"></i><span class="hidden-tablet"> Images</span></a></li>
					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>

    <if="stripos($mmhclass->input->server_vars['http_user_agent'], "MSIE 6.0") !== false && stripos($mmhclass->input->server_vars['http_user_agent'], "MSIE 8.0") === false && stripos($mmhclass->input->server_vars['http_user_agent'], "MSIE 7.0") === false">
       <div class="slideout_warning">
            <span class="picture ie_picture">&nbsp;</span>
            
            <span class="info">
                <h1>Unsupported Web Browser</h1>
                The web browser that you are running is not supported. 
                Please try one of the following: <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" target="_new">Internet Explorer 9</a>, <a href="http://www.apple.com/safari/" target="_new">Safari</a>, <a href="http://firefox.com" target="_new">Firefox</a>, or <a href="http://opera.com" target="_new">Opera</a>, <a href="http://www.google.com/chrome/" target="_new">Google Chrome</a>.
            </span>
        </div>
    <else>
        <noscript>
           <div class="slideout_warning">
                <span class="picture">&nbsp;</span>
                
                <span class="info">
                    <h1>JavaScript is Disabled!</h1>
                    Your browser currently has JavaScript disabled or does not support it.
                    Since this website uses JavaScript extensively it is recommended to <a href="http://support.microsoft.com/gp/howtoscript" target="_new">>enable it</a>.
                </span>
            </div>
        </noscript>
    </endif>

			<div id="content" class="span10">
			<!-- content starts -->
