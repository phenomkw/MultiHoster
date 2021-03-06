<?php
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
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