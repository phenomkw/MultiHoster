<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1252358960 - Monday, September 07, 2009, 05:29:20 PM EDT -0400
	// ======================================== /
	
	require_once "{$mmhclass->info->root_path}source/language/modules/fileinfo.php";
	
	header("Content-Type: text/plain;");
	header(sprintf("Content-Disposition: inline; filename=fileinfo_html_%s.txt;", mt_rand(1000, 9999)));
	
	if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['file']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
		exit($mmhclass->templ->lightbox_error($mmhclass->lang['001']));
	} elseif ($mmhclass->funcs->is_file($mmhclass->input->get_vars['file'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']), true) == false) {
		exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['002'], $mmhclass->image->basename($mmhclass->input->get_vars['file']))));
	} else {
		$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
		$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
		$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, true);
		$thumbnail_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']);
		$mmhclass->templ->templ_vars[] = array(
			"BASE_URL" => $mmhclass->info->base_url,
			"FILENAME" => $filename,
			"MIME_TYPE" => $file_info['mime'],
			"IMAGE_WIDTH" => $file_info['width'],
			"IMAGE_HEIGHT" => $file_info['height'],
			"FILE_EXTENSION" => $file_info['extension'],
			"THUMBNAIL_HEIGHT" => $thumbnail_info['height'],
			"BANDWIDTH_USAGE" => $file_info['logs']['bandwidth'],
			"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
			"UPLOAD_PATH" => $mmhclass->info->config['upload_path'].$rnd_dir_name,
			"THUMBNAIL" => $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail'],
			"TOTAL_FILESIZE" => $mmhclass->image->format_filesize($file_info['bits']),
			"DATE_UPLOADED" => date($mmhclass->info->config['date_format'], $file_info['mtime']),
			"TOTAL_RATINGS" => $mmhclass->funcs->format_number($file_info['rating']['total_votes']),
			"BANDWIDTH_USAGE_FORMATTED" => $mmhclass->image->format_filesize($file_info['logs']['bandwidth']),
			"REAL_FILENAME" => (($mmhclass->funcs->is_null($file_info['logs']['original_filename']) == false) ? $file_info['logs']['original_filename'] : $filename),
		);
		exit($mmhclass->templ->parse_template("fileinfo"));
	}
	
?>
