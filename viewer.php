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
	require_once "{$mmhclass->info->root_path}source/language/viewer.php";
	if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['file']) == true) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);
	} elseif ($mmhclass->funcs->is_file($mmhclass->input->get_vars['file'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']), true) == false) {
		$mmhclass->templ->error(sprintf($mmhclass->lang['003'], $mmhclass->image->basename($mmhclass->input->get_vars['file'])), true);
	} elseif ($mmhclass->image->is_image($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']).$mmhclass->input->get_vars['file']) == false) {
		$mmhclass->templ->error(sprintf($mmhclass->lang['004'], $mmhclass->image->basename($mmhclass->input->get_vars['file'])), true);
	} else {
		$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
		$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
		$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name .$filename, true);
		
		$original_filename = (($mmhclass->funcs->is_null($file_info['logs']['original_filename']) == false) ? $file_info['logs']['original_filename'] : $filename);
		
		$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name'], $original_filename);
	
		if (stripos($mmhclass->input->server_vars['http_referer'], $mmhclass->info->base_url) === false) {
			$new_viewer_click = $mmhclass->db->query("UPDATE `[1]` SET `viewer_clicks` = `viewer_clicks` + 1 WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename));
		}
		
		if ($mmhclass->input->get_vars['act'] == "rate_it" && isset($mmhclass->input->post_vars['rating_id']) == true) {
			$mmhclass->templ->templ_globals['new_file_rating'] = true;
			
			if (in_array($mmhclass->input->server_vars['remote_addr'], explode("|", $file_info['rating']['voted_by'])) == true) {
				$new_rating_html = $failed_image_rating = $mmhclass->templ->error($mmhclass->lang['005'], false);
			} else {
				if ($mmhclass->funcs->is_null($file_info['rating']['rating_id']) == true) {
					$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `total_rating`, `total_votes`, `voted_by`) VALUES ('[2]', '0', '0', '');", array(MYSQL_FILE_RATINGS_TABLE, $filename));
				}
				
				$mmhclass->db->query("UPDATE `[1]` SET `total_rating` = `total_rating` + '[2]', `total_votes` = `total_votes` + 1, `voted_by` = '[3]' WHERE `filename` = '[4]';", array(MYSQL_FILE_RATINGS_TABLE, $mmhclass->input->post_vars['rating_id'], "{$file_info['rating']['voted_by']}|{$mmhclass->input->server_vars['remote_addr']}", $filename));
				
				$new_rating_html = $mmhclass->templ->message(sprintf($mmhclass->lang['006'], $filename), false);
			}
		}
		
		$mmhclass->templ->templ_globals['file_info'] = $file_info;
		$image_size = $mmhclass->image->scaleby_maxwidth($filename, 940,  true, $rnd_dir_name);
		
		$mmhclass->templ->templ_vars[] = array(
			 "FILENAME" => $filename,
			 "MIME_TYPE" => $file_info['mime'],
			 "IMAGE_WIDTH" => $file_info['width'],
			 "REAL_FILENAME" => $original_filename,	
			 "NEW_RATING_HTML" => $new_rating_html,
			 "IMAGE_HEIGHT" => $file_info['height'],
			 "HIDDEN_COMMENT" => $file_info['comment'],
			 "FILE_EXTENSION" => $file_info['extension'],
			 "PAGE_URL" => 'http://'.$mmhclass->input->server_vars['http_host'].$mmhclass->input->server_vars['request_uri'],
			 "UPLOAD_PATH" => $mmhclass->info->config['upload_path'].$rnd_dir_name ,
			 "FILE_LINKS" => $mmhclass->templ->file_results($filename),
			 "TOTAL_FILESIZE" => $mmhclass->image->format_filesize($file_info['bits']),
			 "IMAGE_VIEWS" => $mmhclass->funcs->format_number($file_info['logs']['image_views']),
			 "DATE_UPLOADED" => date($mmhclass->info->config['date_format'], $file_info['mtime']),
			 "IMAGE_RESIZE" => (($mmhclass->funcs->is_null($image_size['h']) == false) ? "width: {$image_size['w']}px; height: {$image_size['h']}px;" : NULL),
			 "VIEWER_CLICKS" => $mmhclass->funcs->format_number((isset($new_viewer_click) == false) ? $file_info['sinfo']['viewer_clicks'] : ($file_info['sinfo']['viewer_clicks'] + 1)),
			 "TOTAL_RATINGS" => $mmhclass->funcs->format_number((isset($new_rating_html) == true && isset($failed_image_rating) == false) ? ($file_info['rating']['total_votes'] + 1) : $file_info['rating']['total_votes']),
	  	);
		$mmhclass->templ->output("viewer");
	}
	
?>