<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1251326120 - Wednesday, August 26, 2009, 06:35:20 PM EDT -0400
	// ======================================== /

	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/info.php";

	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);

	switch ($mmhclass->input->get_vars['act']) {
		case "about_us":
			$mmhclass->templ->page_title .= $mmhclass->lang['002'];
			
			$mmhclass->templ->templ_vars[] = array(
				"SITE_NAME" => $mmhclass->info->config['site_name'],
				"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_FILE_STORAGE_TABLE)))),
			);
			
			$mmhclass->templ->output("info", "about_us_page");
			break;
		case "rules":
			$mmhclass->templ->page_title .= $mmhclass->lang['003'];
			
			$mmhclass->templ->templ_vars[] = array(
				"SITE_NAME" => $mmhclass->info->config['site_name'],
				"MODIFIED_DATE" => date($mmhclass->info->config['date_format'], filemtime("{$mmhclass->info->root_path}source/public_html/info.tpl")),
			);
			
			$mmhclass->templ->output("info", "terms_of_service_page");
			break;
		case "privacy_policy":
			$mmhclass->templ->page_title .= $mmhclass->lang['004'];
			
			$mmhclass->templ->templ_vars[] = array("SITE_NAME" => $mmhclass->info->config['site_name']);
			
			$mmhclass->templ->output("info", "privacy_policy_page");
			break;
		default: 
			$mmhclass->templ->error($mmhclass->lang['005'], true);
	}

?>