<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1252857541 - Sunday, September 13, 2009, 11:59:01 AM EDT -0400
	// ======================================== /

	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/links.php";

	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);

	$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
	$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
	if ($mmhclass->funcs->is_null($filename) == true) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);
	} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name, true) == false) {
		$mmhclass->templ->error(sprintf($mmhclass->lang['003'], $filename), true);
	} else {
		$mmhclass->templ->html = $mmhclass->templ->file_results($filename);
		$mmhclass->templ->output();
	}
		
?>