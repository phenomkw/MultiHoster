<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253007951 - Tuesday, September 15, 2009, 05:45:51 AM EDT -0400
	// ======================================== /
	
	$mmhclass->templ->templ_globals['upload_type'] = ((isset($mmhclass->input->get_vars['url']) == true) ? "url" : "std");
	
	$mmhclass->templ->templ_vars[] = array("BASE_URL" => $mmhclass->info->base_url);
	
	exit($mmhclass->templ->parse_template("tools", "iframe_uploader"));

?>