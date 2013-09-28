<?php 
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1250799957 - Thursday, August 20, 2009, 04:25:57 PM EDT -0400
	// ======================================== /
	
	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/tools.php";
	
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);

	$mmhclass->templ->templ_vars[] = array(
		"BASE_URL" => $mmhclass->info->base_url,
		"SITE_NAME" => $mmhclass->info->config['site_name'],
	);

	switch ($mmhclass->input->get_vars['act']) {
		case "url":
	
	$mmhclass->templ->output("tools2", "tools_page2");
			break;
		case "pc_url":
	
	$mmhclass->templ->output("tools3", "tools_page3");
			break;
		default:
	
	$mmhclass->templ->output("tools", "tools_page");
	}
?>