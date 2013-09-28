<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.3
	// Copyright (c) 2007-2011 Mihalism Script
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// ======================================== /
	
	if (is_object($mmhclass) == false) { exit; }
	
	$mmhclass->templ->templ_globals['upload_type'] = (($mmhclass->funcs->is_null($mmhclass->input->get_vars['url']) == false) ? "url" : "std");
	
	$mmhclass->templ->templ_vars[] = array("BASE_URL" => $mmhclass->info->base_url);
	
	exit($mmhclass->templ->parse_template("tools2", "iframe_uploader2"));

?>
