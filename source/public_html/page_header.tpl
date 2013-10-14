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
    
    <title><# PAGE_TITLE #></title>
   
    <meta name="version" content="MultiHoster v<# VERSION #>" />
    <meta name="description" content="<# SITE_NAME #> is an easy image hosting solution for everyone." />
    <meta name="keywords" content="image hosting, image hosting service, multiple image hosting, unlimited bandwidth, quick image hosting" />
    
    <base href="<# BASE_URL #>" />

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

<!--	<script type="text/javascript" src="http://gettopup.com/releases/latest/top_up-min.js"></script>-->
	<script type="text/javascript" src="source/includes/scripts/base64_min.js"></script>
	<script type="text/javascript" src="source/includes/scripts/jquery.dropdown.js"></script>
	<script type="text/javascript" src="source/includes/scripts/genjscript.js"></script>

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
	<script src="source/includes/scripts/modal.js"></script>

</head>

<body>
		<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="index.php" style="text-decoration: none"> <img alt="" src="img/logo20.png" /> <span><# SITE_NAME #></span></a>
				<if="$mmhclass->info->is_user == true">
				<!-- user dropdown starts -->
								<div class="btn-group pull-right">
								  <a class="btn btn-primary"><i class="icon-user icon-white"></i> <# USERNAME #></a>
								  <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href=""><span class="caret"></span></a>
								  <ul class="dropdown-menu">
                 <if="$mmhclass->info->config['seo_urls'] == 1">
								  <li><a href="my-gallery">My Gallery</a></li>
								  <li><a href="my-settings">Settings</a></li>
                  <li class="divider"></li>
								  <li><a href="logout">Log Out</a></li>
                 <else>
								  <li><a href="users.php?act=gallery"><i class="icon-glass"></i> My Gallery</a></li>
								  <li><a href="users.php?act=settings"><i class="icon-cog"></i> Settings</a></li>
                 <if="$mmhclass->info->is_admin == true">
								  <li class="divider"></li>
								  <li><a href="admin.php"><i class="icon icon-red icon-wrench"></i> AdminCP</a></li>
                 </endif>
                 <if="$mmhclass->info->is_mod == true || $mmhclass->info->is_admin == true">
								  <li><a href="modcp.php"><i class="icon icon-green icon-wrench"></i> ModCP</a></li>
                 </endif>
								  <li class="divider"></li>
								  <li><a href="users.php?act=logout"><i class="icon-off"></i> Log Out</a></li>
                 </endif>
								  </ul>
								</div>
				<!-- user dropdown ends -->
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
		</endif>
			</div>
		</div>
	</div>
	<!-- topbar ends -->
						<div class="alert alert-block " style="width: 90%; margin-left: auto; margin-right: auto;">
							<h4 class="alert-heading">Warning!</h4>
							<p>This version is in Alpha testing, there will more than likey be a few bugs so please don't expect too much right now. Thanks.</p>
					<if="$mmhclass->info->is_user == false">
							<h4 class="alert-heading">Guest</h4>
							<p>Please login or Register for a account to access all of our features.</p>
					</endif>
					</div>
		<div class="container-fluid">
		<div class="row-fluid">

			<!-- left menu starts -->
			<div class="span2 main-menu-span">
				<div class="well nav-collapse sidebar-nav">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<li class="nav-header hidden-tablet"><i class="icon-info-sign"></i> Menu</li>
						<li><a class="ajax-link" href="index.php"><i class="icon-home"></i><span class="hidden-tablet"> Index</span></a></li>
						<if="$mmhclass->info->is_user == true">
						<li><a class="ajax-link" href="index.php?url=1"><i class="icon-home"></i><span class="hidden-tablet"> URL Upload</span></a></li>
						<li><a class="ajax-link" href="index.php?zip"><i class="icon-home"></i><span class="hidden-tablet"> Zip Upload</span></a></li>
						</endif>
						<li class="nav-header hidden-tablet"><i class="icon-info-sign"></i> Galleries</li>
						<li><a class="ajax-link" href="gallery.php"><i class="icon-picture"></i><span class="hidden-tablet"> Public Gallery</span></a></li>
						<li><a class="ajax-link" href="users.php?act=user_list"><i class="icon-picture"></i><span class="hidden-tablet"> Member Galleries</span></a></li>
						<li><a class="ajax-link" href="index.php?do_random=true"><i class="icon-picture"></i><span class="hidden-tablet"> Random Image</span></a></li>
						<li class="nav-header hidden-tablet"><i class="icon-info-sign"></i> Webmaster Tools</li>
						<li><a href="tools.php"><i class="icon-book"></i><span class="hidden-tablet"> Computer upload</span></a></li>
        				<li><a href="tools2.php"><i class="icon-book"></i><span class="hidden-tablet"> URL upload</span></a></li>
        				<li><a href="tools3.php"><i class="icon-book"></i><span class="hidden-tablet"> Computer & URL upload</span></a></li>
				<if="$mmhclass->info->is_user == false">
						<li class="nav-header hidden-tablet"><i class="icon-info-sign"></i> Welcome Guest</li>
				<if="$mmhclass->info->config['seo_urls'] == 1">
						<li><a href="register">Register</a></li>
				<else>
						<li><a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=login', 'login_lightbox');"><i class="icon-book"></i><span class="hidden-tablet"> Login</span></a></li>
						<li><a href="users.php?act=register&amp;return=<# RETURN_URL #>"><i class="icon-book"></i><span class="hidden-tablet"> Register</span></a></li>
				</endif>
				</endif>
						<if="$mmhclass->info->config['show_random'] == '1'">
						<li class="nav-header hidden-tablet"><i class="icon-info-sign"></i> Random Image</li>
    					<li><a></a></li>
    					<li id="rnd_imgs"><br/><center><while id="random_images_whileloop">
    					<# RANDOM_IMAGES #>
    					</endwhile></center>
    					<script type="text/javascript">
      					TopUp.addPresets({
        				"#rnd_imgs a": {
          				title: "Gallery {alt} ({current} of {total})",
          				group: "rnd_imgs",
	  					layout: "quicklook",
          				readAltText: 1,
          				shaded: 0}
      					});
    				</script><br/></li>
    				</endif>
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
