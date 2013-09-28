<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-us" xml:lang="en-us">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta http-equiv="imagetoolbar" content="no" />
    <title>Moderator Control Panel</title>
    <link rel="shortcut icon" href="css/images/favicon.ico" />
    <if="$mmhclass->info->config['background'] == 0">
    <link href="css/default.css" rel="stylesheet" type="text/css" media="screen" />
    </endif>
    <if="$mmhclass->info->config['background'] == 1">
    <link href="css/dots.css" rel="stylesheet" type="text/css" media="screen" />
    </endif>
    <if="$mmhclass->info->config['background'] == 2">
    <link href="css/grundge.css" rel="stylesheet" type="text/css" media="screen" />
    </endif>
    <if="$mmhclass->info->config['1024'] == 1">
    <link href="css/1024.css" rel="stylesheet" type="text/css" media="screen" />
    <else>
    <link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
    </endif>
    
    <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" />
	<![endif]-->
	<script type="text/javascript" src="http://gettopup.com/releases/latest/top_up-min.js"></script>
	<script type="text/javascript" src="source/includes/scripts/img_preview.js"></script>
	<script type="text/javascript" src="source/includes/scripts/base64_min.js"></script>
	<script type="text/javascript" src="source/includes/scripts/jquery.dropdown.js"></script>
	<script type="text/javascript" src="source/public_html/modcp/modgenjscript.js"></script>
</head>
<body class="page_cell">
	<div class="logo">&nbsp;</div>
<div align="center">
<ul id="topnav">
			<li><a href="modcp.php">Dashboard</a></li>
			<li><a href="index.php">Site Home</a></li>
			<li><a href="modcp.php?act=ban_control">Ban Control</a></li>
			<li><a href="modcp.php?act=user_list">User Management</a></li>
</ul></div>
	<div id="page_body" class="page_body">