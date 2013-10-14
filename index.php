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
	require_once "{$mmhclass->info->root_path}source/language/home.php";
	
include "func.ban.php";
checkban($_SERVER['REMOTE_ADDR']);
	// Module file loader
	if (isset($mmhclass->input->get_vars['module']) == true) {
		$module_name = $mmhclass->image->basename($mmhclass->input->get_vars['module']);
		
		if ($mmhclass->funcs->file_exists("{$mmhclass->info->root_path}source/modules/{$module_name}.php") == true) {
			require_once "{$mmhclass->info->root_path}source/modules/{$module_name}.php"; 
			
			exit;	
		}
	}
	
	// Upload progress bar
	if ($mmhclass->input->get_vars['act'] == "upload_in_progress") {
		exit($mmhclass->templ->parse_template("home", "upload_in_progress_lightbox"));
	}
	// Random Image
	if (isset($mmhclass->input->get_vars['do_random']) == true) {
		$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `is_private` = '0' AND `gallery_id` = '0' ORDER BY RAND() LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE));
		
		if ($mmhclass->db->total_rows($sql) !== 1) {
			$mmhclass->templ->error($mmhclass->lang['006'], true);
		} else {	
			$file_info = $mmhclass->db->fetch_array($sql);
			if ($mmhclass->info->config['seo_urls'] == '1') {
			header("Location: {$mmhclass->info->base_url}random/{$file_info['filename']}.html");
			}else {
				header("Location: {$mmhclass->info->base_url}viewer.php?is_random={$file_info['file_id']}&file={$file_info['filename']}");
			}
			exit;
		}
	}
	if (isset($mmhclass->input->post_vars['do_recaptcha']) == true && isset($mmhclass->input->post_vars['recaptcha_challenge_field']) == true  &&  isset($mmhclass->input->post_vars['recaptcha_response_field']) == true ) {
			$resp = recaptcha_check_answer($mmhclass->info->config['recaptcha_private'],
					$mmhclass->input->server_vars['remote_addr'],
					$mmhclass->input->post_vars['recaptcha_challenge_field'],
					$mmhclass->input->post_vars['recaptcha_response_field']);
			if ($resp->is_valid) {die("success");}
			else {die ("The reCAPTCHA wasn't entered correctly. Go back and try it again."."(reCAPTCHA said: " . $resp->error . ")");}
	}
	
	// Disable uploading? -- Does not apply to administrators
	if ($mmhclass->info->config['uploading_disabled'] == true && $mmhclass->info->is_admin == false) {
		$mmhclass->templ->page_title = $mmhclass->lang['005'];
		
		$mmhclass->templ->error($mmhclass->lang['004'], true);
	}

	// Disable uploading for Guests only?
	if ($mmhclass->info->config['useronly_uploading'] == true && $mmhclass->info->is_user == false) {
		$mmhclass->templ->templ_vars[] = array(
				"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				"SITE_NAME" => $mmhclass->info->config['site_name'],
				"MAX_FILESIZE" => $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize']),
				"FILE_EXTENSIONS" => $file_extensions,
		);
			$mmhclass->templ->output("home", "upl_login_page");
	}
	
	// Upload Layout Preview Lightbox
	if (isset($mmhclass->input->get_vars['layoutprev']) == true) {
		$mmhclass->templ->templ_vars[] = array(
			"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
			"IMAGE_HEIGHT" => (($mmhclass->input->get_vars['layoutprev'] == "std") ? 280 : 454),
			"PREVIEW_TYPE" => (($mmhclass->input->get_vars['layoutprev'] == "std") ? "std" : "bx"),
		);
		
		exit($mmhclass->templ->parse_template("home", "upload_layout_preview_lightbox"));
	}
		
	// Normal and URL upload page
	$last_extension = end($mmhclass->info->config['file_extensions']);
	
	// Required Stuff for Uploadify 
	foreach ($mmhclass->info->config['file_extensions'] as $this_extension) {
		$file_extensions .= sprintf((($last_extension == $this_extension) ? "{$mmhclass->lang['003']} .%s" : ".%s, "), strtoupper($this_extension));
		$allowed_file_extensions .= $this_extension.',';
	}
	$user_var = 0;
	$maximum_files = $mmhclass->info->config['max_guest_simul_upload'];
	if ($mmhclass->info->is_user == true) {
		$user_var = 1;
		$maximum_files = $mmhclass->info->config['max_user_simul_upload'];
	}
	if ($mmhclass->info->is_admin == true) {
		$user_var = 9;
		$maximum_files = 1000;
	}
	 // Required Stuff for Uploadify 
	 
	/* "Upload To" addon developed by Josh D. of www.hostmine.us */
	if ($mmhclass->info->is_user == true) {
		$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' LIMIT 50;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->info->user_data['user_id']));
		
		if ($mmhclass->db->total_rows($sql) < 1) {
			$mmhclass->templ->templ_globals['hide_upload_to'] = true;
		} else {
			$template_id = ((isset($mmhclass->input->get_vars['url']) == false) ? "normal_upload_page" : "url_upload_page");
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
       $mmhclass->templ->templ_globals['get_whileloop']["albums_pulldown_whileloop"] = true;
				if ($row['is_private'] == 1) {
				$mmhclass->templ->templ_vars[] = array(
					"ALBUM_ID" => $row['album_id'],
					"ALBUM_NAME" => $row['album_title'].' (private)',
				);
				}else{
				$mmhclass->templ->templ_vars[] = array(
					"ALBUM_ID" => $row['album_id'],
					"ALBUM_NAME" => $row['album_title'],
				);
				}
				$mmhclass->templ->templ_globals['albums_pulldown_whileloop'] .= $mmhclass->templ->parse_template("home", $template_id);
				unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
			}
		}
	}
    
	/* Random Images Mod by Josh D. */
	$sql = $mmhclass->db->query("SELECT * FROM `mmh_file_storage` WHERE `is_private` = '0' AND `gallery_id` = '0' ORDER BY RAND() LIMIT 4;");
        while ($row = $mmhclass->db->fetch_array($sql)) {
            $mmhclass->templ->templ_globals['get_whileloop']['random_images_whileloop'] = true;
	    if ($mmhclass->info->config['seo_urls'] == '1') {
            $mmhclass->templ->templ_vars[] = array(
        "RANDOM_IMAGES"  => "<a href=\"{$row['filename']}\"><img src=\"thumb.{$row['filename']}\" title=\"{$row['file_title']}\" style=\"padding: 1px; max-width: 100px;max-height: 100px;\"></a>",
            ); } else {
	      $mmhclass->templ->templ_vars[] = array(
        "RANDOM_IMAGES"  => "<a href=\"viewer.php?file={$row['filename']}\"><img src=\"img.php?module=thumbnail&file={$row['filename']}\" title=\"{$row['file_title']}\" style=\"padding: 1px; max-width: 200px;max-height: 200px;\"></a>",
            );}
			//Changed this to show on the 3 home ID's (url,zip,normal) it needs to be added to UPL_login_page for home ID. 
			//would also like to have to show on other pages as well.
			$mmhclass->templ->templ_globals['random_images_whileloop'] .= $mmhclass->templ->parse_template("home", $template_id);
			unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
        }

	$mmhclass->templ->templ_vars[] = array(
		"FILE_EXTENSIONS" => $file_extensions,
		"SITE_NAME" => $mmhclass->info->config['site_name'],
	// Required Stuff for Uploadify
		"RAND_SES_VAR" => mt_rand().md5(microtime()).mt_rand().$user_var,
		"MAX_UL_SIZE" => round($mmhclass->info->config['max_filesize'] / 1024),
		"ALLOWED_TYPES" => $allowed_file_extensions,
		"MAX_SIMUL_FILES" => $maximum_files,
	// Required Stuff for Uploadify
		"MAX_RESULTS" => $mmhclass->info->config['max_results'],
		"MAX_FILESIZE" => $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize']),
		"BOXED_UPLOAD_YES" => (($mmhclass->info->user_data['upload_type'] == "boxed") ? "checked=\"checked\"" : NULL),
        "CAPTCHA_CODE" => recaptcha_get_html($mmhclass->info->config['recaptcha_public']),
        "STANDARD_UPLOAD_YES" => (($mmhclass->info->user_data['upload_type'] == "standard" || $mmhclass->info->is_user == false) ? "checked=\"checked\"" : NULL),
	);
	
	if (isset($mmhclass->input->get_vars['url'])) {
		$mmhclass->templ->page_title = sprintf($mmhclass->lang['002'], $mmhclass->info->config['site_name']);
		$mmhclass->templ->output("home", "url_upload_page");
	}elseif (isset($mmhclass->input->get_vars['zip']) == true) {
		$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
		$mmhclass->templ->output("home", "zip_upload_page");
	}elseif (isset($mmhclass->input->get_vars['upl']) == true) {
		$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
		$mmhclass->templ->output("home", "upl_login_page");
	} else {
		$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
		$mmhclass->templ->output("home", "normal_upload_page");
	}

?>
