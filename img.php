<?php
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
	// IMG Loader for hotlink protection (c) by TheKPM 2011
	// ======================================== /
	require_once "./source/includes/data.php";

	// Module file loader
	if (isset($mmhclass->input->get_vars['module']) == true) {
		$module_name = $mmhclass->image->basename($mmhclass->input->get_vars['module']);

		if ($mmhclass->funcs->file_exists("{$mmhclass->info->root_path}source/modules/{$module_name}.php") == true) {
			require_once "{$mmhclass->info->root_path}source/modules/{$module_name}.php";

			exit;
		}
	}
	
	$filename = $mmhclass->image->basename($mmhclass->input->get_vars['image']);
	$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
	$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);

	header("Content-Type: {$file_info['mime']};");
	
	switch ($mmhclass->input->get_vars['mode']) {
		case "thumb":
			if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name, true) == false) {
				readfile("{$mmhclass->info->root_path}css/images/error404.gif");
			} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']) == false) {
				readfile("{$mmhclass->info->root_path}css/images/no_thumbnail.png");
			} else {
				readfile($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']);
			}
			break;
					
		default:
			if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name, true) == false) {
				readfile("{$mmhclass->info->root_path}css/images/error404.gif");
			} else {
				if ($mmhclass->info->config['hotlink'] == 1 || strpos($_SERVER["HTTP_REFERER"], 'viewer.php')) { 
					readfile($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);
				} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']) == false) {
					readfile("{$mmhclass->info->root_path}css/images/no_thumbnail.png");
				} else {
					readfile($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']);
				}
			}
			break;
	}
	exit;
?>
