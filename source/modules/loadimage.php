<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1252858546 - Sunday, September 13, 2009, 12:15:46 PM EDT -0400
	// ======================================== /
	
	$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
	$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
	if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name, true) == false) {
		header("Content-Type: image/gif;");
		header("Content-Disposition: inline; filename=error404.gif;");
		
		readfile("{$mmhclass->info->root_path}css/images/error404.gif");
	} else {
		$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, true);
		
		header("Content-Type: {$file_info['mime']};");
		header("Content-Disposition: inline; filename={$filename};");
		
		if ($file_info['logs']['bandwidth'] > $mmhclass->info->config['max_bandwidth']) {
			readfile("{$mmhclass->info->root_path}css/images/error509.gif");
		} else {			
			if ($mmhclass->info->config['proxy_images'] == true) {
				$mmhclass->db->query("UPDATE `[1]` SET `bandwidth` = `bandwidth` + '[2]', `image_views` = `image_views` + 1 WHERE `filename` = '[3]';", array(MYSQL_FILE_LOGS_TABLE, $file_info['bits'], $filename));
			}
			
			readfile($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);
		}
	}

	exit;

?>