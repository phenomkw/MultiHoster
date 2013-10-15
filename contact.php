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
	require_once "{$mmhclass->info->root_path}source/language/contact.php";

	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
	
	switch ($mmhclass->input->get_vars['act']) {
		case "contact_us":
			$mmhclass->templ->page_title .= $mmhclass->lang['002'];
			
			$mmhclass->templ->templ_vars[] = array(
				"SITE_NAME" => $mmhclass->info->config['site_name'],
				"USERNAME" => $mmhclass->info->user_data['username'],
				"EMAIL_ADDRESS" => $mmhclass->info->user_data['email_address'],
				"CAPTCHA_CODE" => recaptcha_get_html($mmhclass->info->config['recaptcha_public']),
			);
			
			$mmhclass->templ->output("contact", "contact_us_page");
			break;
		case "contact_us-s":
			$mmhclass->templ->page_title .= $mmhclass->lang['002'];
			
			$recaptcha_check = recaptcha_check_answer($mmhclass->info->config['recaptcha_private'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->post_vars["recaptcha_challenge_field"], $mmhclass->input->post_vars["recaptcha_response_field"]);
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['message_body']) == true) {
				$mmhclass->templ->error($mmhclass->lang['003'], true);
			} elseif ($recaptcha_check->is_valid == false) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['005'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} else {
				$mmhclass->templ->templ_vars[] = array(
					"BASE_URL" => $mmhclass->info->base_url,
					"SITE_NAME" => $mmhclass->info->config['site_name'],
					"USERNAME" => $mmhclass->input->post_vars['username'],
					"EMAIL_ADDRESS" => strtolower($mmhclass->input->post_vars['email_address']),
					"EMAIL_BODY" => str_replace("\n", "<br />", strip_tags($mmhclass->input->post_vars['message_body'])),
				);
				
				$message_body = $mmhclass->templ->parse_template("contact", "contact_us_email"); 

				$email_headers = "MIME-Version: 1.0\r\n";
				$email_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$email_headers .= "From: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_out']}>\r\n";
				$email_headers .= sprintf("Reply-To: %s <%s>\r\n", $mmhclass->funcs->sanitize_string($mmhclass->input->post_vars['username']), strtolower($mmhclass->input->post_vars['email_address']));

				if (mail($mmhclass->info->config['email_in'], sprintf($mmhclass->lang['006'], $mmhclass->info->config['site_name'], mt_rand(100,9999)), $message_body, $email_headers) == true) {
					$mmhclass->templ->message(sprintf($mmhclass->lang['007'], $mmhclass->info->config['site_name']), true);
				} else {
					$mmhclass->templ->error($mmhclass->lang['008']);
				}
			}
			break;
		case "file_report":
			$mmhclass->templ->page_title .= $mmhclass->lang['009'];
			
			$mmhclass->templ->templ_vars[] = array(
				"SITE_NAME" => $mmhclass->info->config['site_name'],
				"USERNAME" => $mmhclass->info->user_data['username'],
				"EMAIL_ADDRESS" => $mmhclass->info->user_data['email_address'],
				"FILENAME" => $mmhclass->image->basename($mmhclass->input->get_vars['file']),
				"CAPTCHA_CODE" => recaptcha_get_html($mmhclass->info->config['recaptcha_public']),
			);
			
			$mmhclass->templ->output("contact", "report_files_page");
			break;
		case "file_report-s":
			$mmhclass->templ->page_title .= $mmhclass->lang['009'];
			
			$recaptcha_check = recaptcha_check_answer($mmhclass->info->config['recaptcha_private'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->post_vars["recaptcha_challenge_field"], $mmhclass->input->post_vars["recaptcha_response_field"]);
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['report_reason']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['filename']) == true) {
				$mmhclass->templ->error($mmhclass->lang['003'], true);
			} elseif ($recaptcha_check->is_valid == false) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['005'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} else {
				$filelist = $mmhclass->image->basename(explode(",", $mmhclass->input->post_vars['filename']));
				
				foreach ($filelist as $id => $filename) {
					if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == false) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['010'], $filename), true);
					} else {
						$mmhclass->templ->templ_globals['get_whileloop'] = true;
						
						$thumbnail = $mmhclass->image->thumbnail_name($filename);
						
						$mmhclass->templ->templ_vars[] = array(
							"FILENAME" => $filename,
							"IMAGE_NUMBER" => ($id + 1),
							"DIRECT_LINK" => $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$filename,
							"DELETE_LINK" => sprintf("%sadmin.php?act=delete_files-d&d=1&files={$filename}", $mmhclass->info->base_url),
							"THUMBNAIL" => (($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$thumbnail) == false) ? "{$mmhclass->info->base_url}css/images/no_thumbnail.png" : $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$thumbnail),
						);
						
						$mmhclass->templ->templ_globals['reported_files_whileloop'] .= $mmhclass->templ->parse_template("contact", "report_files_email");
						unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
					}
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"SITE_NAME" => $mmhclass->info->config['site_name'],
					"USERNAME" => $mmhclass->input->post_vars['username'],
					"EMAIL_ADDRESS" => strtolower($mmhclass->input->post_vars['email_address']),
					"REPORT_REASON" => $mmhclass->lang['013'][$mmhclass->input->post_vars['report_reason']],
				);
				
				$message_body = $mmhclass->templ->parse_template("contact", "report_files_email"); 
	
				$email_headers = "MIME-Version: 1.0\r\n";
				$email_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$email_headers .= "From: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_out']}>\r\n";
				$email_headers .= sprintf("Reply-To: %s <%s>\r\n", $mmhclass->funcs->sanitize_string($mmhclass->input->post_vars['username']), strtolower($mmhclass->input->post_vars['email_address']));

				if (mail($mmhclass->info->config['email_in'], sprintf($mmhclass->lang['011'], $mmhclass->info->config['site_name'], mt_rand(100,9999)), $message_body, $email_headers) == true) {
					$mmhclass->templ->message(sprintf($mmhclass->lang['007'], $mmhclass->info->config['site_name']), true);
				} else {
					$mmhclass->templ->error($mmhclass->lang['008']);
				}
			}
			break;
		default:
			$mmhclass->templ->error($mmhclass->lang['012'], true);
	}

?>