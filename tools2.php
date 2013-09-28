<?php 
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.3
	// Copyright (c) 2007-2011 Mihalism Script
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// ======================================== /
	
	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/tools.php";
	
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
	
	$mmhclass->templ->templ_vars[] = array(
		"BASE_URL" => $mmhclass->info->base_url,
		"SITE_NAME" => $mmhclass->info->config['site_name'],
	);
	
	$mmhclass->templ->output("tools2", "tools_page2");
	
?>
