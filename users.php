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
	require_once "{$mmhclass->info->root_path}source/language/users.php";
	
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
	
	switch ($mmhclass->input->get_vars['act']) {
		case "register":
			$mmhclass->templ->page_title .= $mmhclass->lang['041'];
			
			if ($mmhclass->info->config['registration_disabled'] == true) {
				$mmhclass->templ->error($mmhclass->lang['040'], true);
			} else {
				$mmhclass->templ->templ_vars[] = array(
					"SITE_NAME" => $mmhclass->info->config['site_name'],
					"CAPTCHA_CODE" => recaptcha_get_html($mmhclass->info->config['recaptcha_public']),
					"RETURN_URL" => (($mmhclass->funcs->is_null($mmhclass->input->get_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->get_vars['return']),
				);
				
				$mmhclass->templ->output("users", "registration_page");
			}
			break;
		case "register-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['041'];
			
			$recaptcha_check = recaptcha_check_answer($mmhclass->info->config['recaptcha_private'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->post_vars["recaptcha_challenge_field"], $mmhclass->input->post_vars["recaptcha_response_field"]);
		
			// Lot of checks for keeping your site secure. :-)
			
			if ($mmhclass->info->config['registration_disabled'] == true) {
				$mmhclass->templ->error($mmhclass->lang['040'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username'])  == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['password-c']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['iagree']) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($recaptcha_check->is_valid == false) {
				$mmhclass->templ->error($mmhclass->lang['061'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['005'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} elseif (strlen($mmhclass->input->post_vars['password']) < 6 || strlen($mmhclass->input->post_vars['password']) > 30) {
				$mmhclass->templ->error($mmhclass->lang['006'], true);
			} elseif ($mmhclass->input->post_vars['password'] !== $mmhclass->input->post_vars['password-c']) {
				$mmhclass->templ->error($mmhclass->lang['042'], true);
			} elseif ($mmhclass->funcs->valid_string($mmhclass->input->post_vars['username']) == false || strlen($mmhclass->input->post_vars['username']) < 3 || strlen($mmhclass->input->post_vars['username']) > 30) {
				$mmhclass->templ->error($mmhclass->lang['043'], true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['username']))) == 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['044'], $mmhclass->input->post_vars['username']), true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `email_address` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['email_address']))) == 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['007'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ip_address` = '[2]' LIMIT 5;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->server_vars['remote_addr']))) >= 5) {
				$mmhclass->templ->templ_vars[] = array(
					"BASE_URL" => $mmhclass->info->base_url,
					"SITE_NAME" => $mmhclass->info->config['site_name'],
					"IP_ADDRESS" => $mmhclass->input->server_vars['remote_addr'],
				);
				
				$email_headers = "MIME-Version: 1.0\r\n";
				$email_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$email_headers .= "From: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_out']}>\r\n";
				
				mail($mmhclass->info->config['email_in'], $mmhclass->lang['704'], $mmhclass->templ->parse_template("users", "user_registration_hard_limit"), $email_headers);
				
				$mmhclass->templ->error($mmhclass->lang['992'], true);
			} else {
				$mmhclass->db->query("INSERT INTO `[1]` (`username`, `password`, `email_address`, `ip_address`, `private_gallery`, `time_joined`, `user_group`, `upload_type`) VALUES ('[2]', '[3]', '[4]', '[5]', '0', '[6]', 'normal_user', 'standard');", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['username'], md5($mmhclass->input->post_vars['password']), strtolower($mmhclass->input->post_vars['email_address']), $mmhclass->input->server_vars['remote_addr'], time()));
				
				$mmhclass->templ->message(sprintf($mmhclass->lang['045'], $mmhclass->input->post_vars['username'], $mmhclass->input->post_vars['return']), true);		
			}	
			break;
		case "check_username":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['username']) == false) {
				header("Content-Type: text/plain;"); 
				header("Content-Disposition: inline; filename=username_check.txt;");
				
				echo $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['username']))); exit;
			}
			break;
		case "login":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"RETURN_URL" => urlencode($mmhclass->input->get_vars['return']),
				);
				
				exit($mmhclass->templ->parse_template("users", "login_lightbox"));
			}
			break;
		case "login-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['046'];
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->db->total_rows(($user_data = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' AND `password` = '[3]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['username'], md5($mmhclass->input->post_vars['password']))))) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['047'], true);
			} else {
				setcookie("mmh_user_session", "session_delete", (time() - 60000)); // Delete old cookie with negative expiration time.
				
				$session_id = md5($mmhclass->funcs->random_string(30));
				$mmhclass->info->user_data = $mmhclass->db->fetch_array($user_data);
				$mmhclass->db->query("UPDATE `[1]` SET `ip_address` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->server_vars['remote_addr'], $mmhclass->info->user_data['user_id']));
				$mmhclass->db->query("INSERT INTO `[1]` (session_id, session_start, user_id, user_agent, ip_address) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]');", array(MYSQL_USER_SESSIONS_TABLE, $session_id, time(), $mmhclass->info->user_data['user_id'], $mmhclass->input->server_vars['http_user_agent'], $mmhclass->input->server_vars['remote_addr']));
				
				// The base64 is kinda redundant but serialization is good. - Expire time is now one month. Used to be one year. 
			
				if (setcookie("mmh_user_session", base64_encode(serialize(array("session_id" => $session_id, "user_id" => $mmhclass->info->user_data['user_id']))), (time() + 2629743), $mmhclass->info->script_path, NULL, IS_HTTPS_REQUEST, true) == true) {
					$mmhclass->info->is_user = true;
					$mmhclass->info->is_root = (($mmhclass->info->user_data['user_group'] === "root_admin") ? true : false);
					$mmhclass->info->is_admin = (($mmhclass->info->is_root == true || $mmhclass->info->user_data['user_group'] === "normal_admin") ? true : false);
					$mmhclass->info->is_mod = (($mmhclass->info->user_data['user_group'] === "normal_moderator") ? true : false);
					if ($mmhclass->info->config['seo_urls'] == '1') {
						$mmhclass->templ->message(sprintf($mmhclass->lang['1048'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return'])), true);
					}else {
					$mmhclass->templ->message(sprintf($mmhclass->lang['048'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return'])), true);
				}} else {
					$mmhclass->templ->error($mmhclass->lang['049'], true);
				}
			}
			break;
		case "logout":
			$mmhclass->templ->page_title .= $mmhclass->lang['037'];
			
			if (setcookie("mmh_user_session", "session_delete", (time() - 60000)) == true) {
				// It would probably be a better security practice to delete all sesseions of this user, but we'll just do one. 
				
				$mmhclass->db->query("DELETE FROM `[1]` WHERE `session_id` = '[2]';", array(MYSQL_USER_SESSIONS_TABLE, $mmhclass->info->user_session['session_id']));
				
				$mmhclass->info->is_user = $mmhclass->info->is_admin = $mmhclass->info->is_root = false;
				
				$mmhclass->templ->message($mmhclass->lang['038'], true);
			} else {
				$mmhclass->templ->error($mmhclass->lang['039'], true);
			}
			break;
		case "lost_password":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$mmhclass->templ->templ_vars[] = array("LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div']);
				
				exit($mmhclass->templ->parse_template("users", "forgotten_password_lightbox"));
			}
			break;
		case "lost_password-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['050'];
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->db->total_rows(($user_data = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' AND `email_address` = '[3]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['username'], strtolower($mmhclass->input->post_vars['email_address']))))) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['051'], true);
			} else {
				$user_data = $mmhclass->db->fetch_array($user_data);
				$new_password = $mmhclass->funcs->random_string(12);
				$auth_key = md5($mmhclass->funcs->random_string(50));
				
				$mmhclass->db->query("INSERT INTO `[1]` (auth_key, new_password, user_id, time_requested, ip_address) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]');", array(MYSQL_USER_PASSWORDS_TABLE, $auth_key, md5($new_password), $user_data['user_id'], time(), $mmhclass->input->server_vars['remote_addr']));
				
				$mmhclass->templ->templ_vars[] = array(
					"AUTH_KEY" => $auth_key,
					"NEW_PASSWORD" => $new_password,
					"USERNAME" => $user_data['username'],
					"BASE_URL" => $mmhclass->info->base_url,
					"SITE_NAME" => $mmhclass->info->config['site_name'],
					"ADMIN_EMAIL" => $mmhclass->info->config['email_in'],
				);
				
				$email_headers = "MIME-Version: 1.0\r\n";
				$email_headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$email_headers .= "From: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_out']}>\r\n";
				
				if (mail($user_data['email_address'], sprintf($mmhclass->lang['052'], $mmhclass->info->config['site_name'], mt_rand(1000, 9999)), $mmhclass->templ->parse_template("users", "forgotten_password_email"), $email_headers) == true) {
					$mmhclass->templ->message(sprintf($mmhclass->lang['053'], $user_data['email_address']), true);
				} else {
					$mmhclass->templ->error($mmhclass->lang['054']);
				}
			}
			break;
		case "lost_password-a":
			$mmhclass->templ->page_title .= $mmhclass->lang['055'];
			
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true || $mmhclass->db->total_rows(($new_password = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `auth_key` = '[2]' LIMIT 1;", array(MYSQL_USER_PASSWORDS_TABLE, $mmhclass->input->get_vars['id'])))) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['056'], true);
			} else {
				$new_password = $mmhclass->db->fetch_array($new_password);
				
				$mmhclass->db->query("DELETE FROM `[1]` WHERE `auth_key` = '[2]';", array(MYSQL_USER_PASSWORDS_TABLE, $new_password['auth_key']));
				$mmhclass->db->query("UPDATE `[1]` SET `password` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, $new_password['new_password'], $new_password['user_id']));
				
				$mmhclass->templ->message($mmhclass->lang['057'], true);
			}
			break;
		case "user_list":  case "user-list":
			$mmhclass->templ->page_title .= $mmhclass->lang['034'];
			
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `user_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_USER_INFO_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"USER_ID" => $row['user_id'],
					"USERNAME" => $row['username'],
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					"TIME_JOINED" => date($mmhclass->info->config['date_format'], $row['time_joined']),
					"GALLERY_STATUS" => (($row['private_gallery'] == 1) ? $mmhclass->lang['035'] : $mmhclass->lang['036']),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `is_private` = '0';", array(MYSQL_FILE_STORAGE_TABLE, $row['user_id'])))),
				);
				
				$mmhclass->templ->templ_globals['user_list_whileloop'] .= $mmhclass->templ->parse_template("users", "user_list_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
			}
			
			$mmhclass->templ->templ_vars[] = array("PAGINATION_LINKS" => $mmhclass->templ->pagelinks("users.php?act=user_list", $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_USER_INFO_TABLE)))));
		
			$mmhclass->templ->output("users", "user_list_page");
			break;
		case "imgedit":
			$mmhclass->templ->page_title .= $mmhclass->lang['333'];
			
			if($mmhclass->info->is_user == false){
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			}elseif (!isset($mmhclass->input->get_vars['file_id'])) {
				$mmhclass->templ->error($mmhclass->lang['023'], true);
			}else {$user_check = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `file_id` = '[2]' LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE, (int)$mmhclass->input->get_vars['file_id'])));
			if ($user_check['gallery_id'] !== $mmhclass->info->user_data['user_id']) {
				$mmhclass->templ->error($mmhclass->lang['400'], true);
			}else{
				$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$user_check['dir_name'] .$user_check['filename'], true);
				$mmhclass->templ->templ_vars[] = array(
					"FILE_ID" => $user_check['file_id'],
					"IMAGE_WIDTH" => $file_info['width'],
					"IMAGE_HEIGHT" => $file_info['height'],
					"HALF_W" => ($file_info['width'] / 2),
					"FILENAME" => $user_check['filename'],
					"FILE_TITLE" => $user_check['file_title'],
				);
				$mmhclass->templ->output("users", "img_editor_page");
			}
			}
			
			break;
		case "imgedit_process":
			if($mmhclass->info->is_user == false){
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			}elseif (!isset($mmhclass->input->post_vars['file_id'])) {
				$mmhclass->templ->error($mmhclass->lang['023'], true);
			}else {$user_check = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `file_id` = '[2]' LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE, (int)$mmhclass->input->post_vars['file_id'])));
				if ($user_check['gallery_id'] !== $mmhclass->info->user_data['user_id']) {
					die($mmhclass->info->user_data['user_id'].' !== '.$user_check['gallery_id']);
		//		$mmhclass->templ->error($mmhclass->lang['400'], true);
				}else{
					$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$user_check['dir_name'] .$user_check['filename'], true);
					if ($mmhclass->input->post_vars['rotate'] == 90 || $mmhclass->input->post_vars['rotate'] == 180 || $mmhclass->input->post_vars['rotate'] == -90) {
						if ($mmhclass->input->post_vars['rotate'] == 90){ $rt = -90;} if($mmhclass->input->post_vars['rotate'] == -90){ $rt = 90;} if($mmhclass->input->post_vars['rotate'] == 180){ $rt = 180;}
						if($mmhclass->image->rotateImage($user_check['filename'], $rt , $file_info['extension'], $user_check['dir_name']) == false) {
							die('ROTATE FAILED');}
					} if ($mmhclass->input->post_vars['grayscale'] == 1 && $mmhclass->input->post_vars['sepia'] == 0) {
						if($mmhclass->image->grayscaleImage($user_check['filename'], $file_info['width'], $file_info['height'], $file_info['extension'], $user_check['dir_name']) == false) {
							die('GRAYSCALE FAILED');}
					} if ($mmhclass->input->post_vars['sepia'] == 1) {
						if($mmhclass->image->grayscaleImage($user_check['filename'], $file_info['width'], $file_info['height'], $file_info['extension'], $user_check['dir_name'],true) == false){
							die('SEPIA FAILED');}
					}if ($mmhclass->input->post_vars['flip_h'] == 1 &&  $mmhclass->input->post_vars['flip_v'] == 0){
						if($mmhclass->image->image_flip($user_check['filename'], 'horiz', $user_check['dir_name'], $file_info['width'], $file_info['height'], $file_info['extension']) == false) {
							die('FLIP H FAILED');}
					}if ($mmhclass->input->post_vars['flip_h'] == 0 &&  $mmhclass->input->post_vars['flip_v'] == 1){
						if($mmhclass->image->image_flip($user_check['filename'], 'vert', $user_check['dir_name'], $file_info['width'], $file_info['height'], $file_info['extension']) == false) {
							die('FLIP V FAILED');}
					}if ($mmhclass->input->post_vars['flip_h'] == 1 &&  $mmhclass->input->post_vars['flip_v'] == 1){
						if($mmhclass->image->image_flip($user_check['filename'], 'both', $user_check['dir_name'], $file_info['width'], $file_info['height'], $file_info['extension']) == false) {
							die('FLIP BOTH FAILED');}
					}
					die('fine');
				}	
			}
			break;
		case "checkpw":
			if(!isset($mmhclass->input->post_vars['albumid']) || !isset($mmhclass->input->post_vars['galleryid']) || !isset($mmhclass->input->post_vars['pw'])) {
				die('Got Milk?');
			}
			$mmhclass->db->query("DELETE FROM `[1]` WHERE `valid_till` < '[2]';", array(MYSQL_GALLERY_SESSION_TABLE, (time() - 1)));
			$album_to_check = (int)$mmhclass->input->post_vars['albumid'];
			$gallery_to_check = (int)$mmhclass->input->post_vars['galleryid'];
			$entered_pw = md5($mmhclass->input->post_vars['pw']);
			$database_check = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE,$gallery_to_check,$album_to_check)));
			if ( $database_check['password'] == $entered_pw ) {
				if ($mmhclass->info->is_user == false) {
					$makesession = md5($album_to_check.$mmhclass->input->server_vars["remote_addr"].$mmhclass->input->server_vars["http_user_agent"].$gallery_to_check);
					$user_id = 0;
				}else { $user_id = $mmhclass->info->user_data['user_id']; $makesession = $user_id;}
				$mmhclass->db->query("INSERT INTO `[1]` (`user_session`, `user_id`, `gallery_id`, `album_id`, `valid_till`) VALUES ( '[2]', '[3]', '[4]', '[5]', '[6]' );", array(MYSQL_GALLERY_SESSION_TABLE, $makesession, $user_id, $gallery_to_check, $album_to_check, (time() + 500)));
				die('success');
			}else {
			die('failed');
			}
			break;
			
			
		case "gallery": case "my-gallery":
		var_dump($mmhclass->input->get_vars);
			$mmhclass->templ->page_title .= $mmhclass->lang['033'];
			if (isset($mmhclass->input->get_vars['usergal'])) {
				$uname_to_id =$mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, htmlspecialchars($mmhclass->input->get_vars['usergal']))));
				$mmhclass->info->selected_gallery = (int)$uname_to_id['user_id'];
			}else{$mmhclass->info->selected_gallery = (int)$mmhclass->input->get_vars['gal'];}
			if (isset($mmhclass->input->get_vars['galname'])) {
				if( $mmhclass->funcs->is_null($mmhclass->info->selected_gallery)) {$mmhclass->info->selected_gallery = (int)$mmhclass->info->user_data['user_id'];}
				$galname_to_id = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_title` = '[3]'  LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->info->selected_gallery, htmlspecialchars($mmhclass->input->get_vars['galname']))));
				$mmhclass->info->selected_album = (int)$galname_to_id['album_id'];
			}else {$mmhclass->info->selected_album = (int)$mmhclass->input->get_vars['cat'];}
			$mmhclass->info->user_owned_gallery = (($mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == true || $mmhclass->info->user_data['user_id'] == $mmhclass->info->selected_gallery) ? true : false);
			$mmhclass->info->gallery_owner_data = (($mmhclass->info->user_owned_gallery == false) ? $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->info->selected_gallery))) : $mmhclass->info->user_data);

			$mmhclass->info->gallery_url = sprintf("%susers.php?act=gallery%s", $mmhclass->info->base_url, (($mmhclass->info->user_owned_gallery == true) ? NULL : "&amp;gal={$mmhclass->info->gallery_owner_data['user_id']}"));
			$mmhclass->info->gallery_url_full = sprintf("%s%s", $mmhclass->info->gallery_url, (($mmhclass->funcs->is_null($mmhclass->info->selected_album) == true) ? NULL : "&amp;cat={$mmhclass->info->selected_album}"));
			$cur_gal_data = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE,$mmhclass->info->gallery_owner_data['user_id'],$mmhclass->info->selected_album)));
			if ($mmhclass->info->is_user == false) { $UID = 0; $search_sess = md5($mmhclass->info->selected_album.$mmhclass->input->server_vars["remote_addr"].$mmhclass->input->server_vars["http_user_agent"].$mmhclass->info->selected_gallery); }
			if ($mmhclass->info->is_user == true) { $UID = $mmhclass->info->user_data['user_id']; $search_sess = $UID;}
			if ($mmhclass->info->user_owned_gallery == true && $mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->info->gallery_owner_data['user_id']) == true && $mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == false) {
				$mmhclass->templ->error($mmhclass->lang['062'], true);
			} elseif ($mmhclass->info->is_admin == false && $mmhclass->info->user_owned_gallery == false && $mmhclass->info->gallery_owner_data['private_gallery'] == 1) {
				$mmhclass->templ->error($mmhclass->lang['059'], true);
			}elseif ($cur_gal_data['is_private'] == 1 && $mmhclass->info->is_admin == false && $mmhclass->info->is_mod == false && $mmhclass->info->user_data['user_id'] !== $cur_gal_data['gallery_id']){
				$mmhclass->templ->error($mmhclass->lang['059'], true);
			}elseif ( $cur_gal_data['has_pw'] == 1 && $mmhclass->info->is_admin == false && $mmhclass->info->is_mod == false && $mmhclass->info->user_data['user_id'] !== $cur_gal_data['gallery_id'] && $mmhclass->funcs->has_valid_session($mmhclass->info->selected_album,$mmhclass->info->selected_gallery, $UID, $search_sess)  == false) {
			
			
				$mmhclass->templ->templ_vars[] = array(
					"ALBUM_ID" => $mmhclass->info->selected_album,
					"GALLERY_ID" =>$mmhclass->info->gallery_owner_data['user_id'],
				);
				
				$mmhclass->templ->output("users", "album_has_pw_page");
			}else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') [[1]] ORDER BY `file_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search'])), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL));
				
				if ($mmhclass->db->total_rows($sql) < 1) {
					$mmhclass->templ->templ_globals['empty_gallery'] = true;
				} else {
					$mmhclass->templ->templ_globals['file_options'] = (($mmhclass->info->user_owned_gallery == true) ? true : false);
						
					while ($row = $mmhclass->db->fetch_array($sql)) {
						$break_line = (($tdcount >= 4) ? true : false);
						$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
						$tdcount++;
						
						if ($mmhclass->info->user_data['user_id'] === $mmhclass->info->gallery_owner_data['user_id']) {
						$mmhclass->templ->templ_vars[] = array(
							"FILE_ID" => $row['file_id'],
							"FILENAME" => $row['filename'],
							"FILE_TITLE" => $row['file_title'],
							"EDITOR_LINK" => '<a href="users.php?act=imgedit&file_id='.$row['file_id'].'" alt="Edit Image"><img border="0" src="./css/images/edit.png" alt="Download"  valign="center" title="Download" class="button1" /></a>',
							"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
							"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
						);

							$gallery_html .= $mmhclass->templ->parse_template("users", "user_gallery_layout");

						}else{
							
						$mmhclass->templ->templ_vars[] = array(
							"FILE_ID" => $row['file_id'],
							"FILENAME" => $row['filename'],
							"FILE_TITLE" => $row['file_title'],
							"EDITOR_LINK" => '',
							"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
							"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
						);

						$gallery_html .= $mmhclass->templ->parse_template("global", "global_gallery_layout");
						}
						unset($break_line, $mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
					}
				}
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' LIMIT 50;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->info->gallery_owner_data['user_id']));
				if ($mmhclass->info->user_data['user_id'] == $mmhclass->info->selected_gallery || $mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == true) { $cururl = '/my-gallery/'; $curloopulr = $cururl;}else {$cururl  = 'user-gallery/'; $curloopulr = $cururl.$mmhclass->info->gallery_owner_data['username'].'/';}
				while ($row = $mmhclass->db->fetch_array($sql)) {
					$mmhclass->templ->templ_globals['get_whileloop'] = true;
					if ($mmhclass->info->user_owned_gallery == false && $row['is_private'] == 1 && $mmhclass->info->is_admin == false && $mmhclass->info->is_mod == false ) { continue;}
					if ($row['album_id'] == $mmhclass->info->selected_album) {
						$curalbum = $row;
					}
					
					
					$mmhclass->templ->templ_vars[] = array(
						"ALBUM_ID" => $row['album_id'],
						"ALBUM_NAME" => $row['album_title'],
						"GALLERY_URL" => $mmhclass->info->gallery_url,
						"USER_NAME" => $curloopulr,
						"FULL_GALLERY_URL" => $mmhclass->info->gallery_url_full,
						"RETURN_URL" => base64_encode($mmhclass->info->page_url),
						"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' [[1]];", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $row['album_id']), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL)))),
					);
					$mmhclass->templ->templ_globals['album_pulldown_whileloop'] .= $mmhclass->templ->parse_template("users", "my_gallery_page");
					unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
				}
				if ($mmhclass->info->config['seo_urls'] == '1' && $mmhclass->info->user_owned_gallery == true) {
					$pag_lnks = $mmhclass->templ->pagelinks(sprintf("%s%s", $cururl, (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true) ? NULL : sprintf("&amp;search=%s", urldecode($mmhclass->input->get_vars['search'])))), $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') [[1]] ORDER BY `file_id` DESC;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search'])), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL))));
				}elseif ($mmhclass->info->config['seo_urls'] == '1' && $mmhclass->info->user_data['user_id'] != $mmhclass->info->selected_gallery){
					$pag_lnks = $mmhclass->templ->pagelinks(sprintf("%s%s/%s", $cururl,$mmhclass->info->gallery_owner_data['username'], (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true) ? NULL : sprintf("&amp;search=%s", urldecode($mmhclass->input->get_vars['search'])))), $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') [[1]] ORDER BY `file_id` DESC;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search'])), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL))));
				}else{$pag_lnks = $mmhclass->templ->pagelinks(sprintf("%s%s", $mmhclass->info->gallery_url_full, (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true) ? NULL : sprintf("&amp;search=%s", urldecode($mmhclass->input->get_vars['search'])))), $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') [[1]] ORDER BY `file_id` DESC;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search'])), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL))));}
				
				$mmhclass->templ->templ_vars[] = array(
					"GALLERY_HTML" => $gallery_html,		
					"GALLERY_URL" => $mmhclass->info->gallery_url,
					"CURRENT_PAGE" => $mmhclass->info->current_page,
					"FULL_GALLERY_URL" => $mmhclass->info->gallery_url_full,
					"USER_NAME" => $curloopulr,
					"RETURN_URL" => base64_encode($mmhclass->info->page_url),
					"GALLERY_ID" => $mmhclass->info->gallery_owner_data['user_id'],
					"IMAGE_SEARCH" => urldecode($mmhclass->input->get_vars['search']),
					"GALLERY_OWNER" => $mmhclass->info->gallery_owner_data['username'],
					"ALBUM_NAME" => (($mmhclass->funcs->is_null($curalbum['album_title']) == true) ? NULL : "&raquo; {$curalbum['album_title']}"),
					"EMPTY_GALLERY" => $mmhclass->templ->message((($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == false) ? $mmhclass->lang['675'] : $mmhclass->lang['058']), false),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' [[1]];", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id']), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL)))),
					"TOTAL_ROOT_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '0' [[1]];", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id']), array(($mmhclass->info->user_owned_gallery == false) ? " AND `is_private` = 0" : NULL)))),
					"PAGINATION_LINKS" => $pag_lnks,
					);

				$mmhclass->templ->output("users", "my_gallery_page");
			}
			break;
		case "rename_file_title":
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['file']) == true) {
				$mmhclass->templ->error($mmhclass->lang['023'], true);
			} elseif ($mmhclass->funcs->is_file($mmhclass->input->get_vars['file'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']), true, $mmhclass->info->user_data['user_id']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['024'], $mmhclass->image->basename($mmhclass->input->get_vars['file'])), true);
			} else {			
				$new_title = htmlentities($mmhclass->input->get_vars['title']);
				
				$mmhclass->db->query("UPDATE `[1]` SET `file_title` = '[2]' WHERE `filename` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $new_title, $mmhclass->image->basename($mmhclass->input->get_vars['file'])));
				
				exit($new_title);
			}
			break;
		case "move_files":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$files2move = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
				
				foreach ($files2move as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['023']));
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false) {
						exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['024'], $filename)));
					} 
				}
				
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->info->user_data['user_id']));
				
				while ($row = $mmhclass->db->fetch_array($sql)) {
					$mmhclass->templ->templ_globals['get_whileloop'] = true;
					
					$mmhclass->templ->templ_vars[] = array(
						"ALBUM_ID" => $row['album_id'],
						"ALBUM_NAME" => $row['album_title'],
					);
					
					$mmhclass->templ->templ_globals['album_options_whileloop'] .= $mmhclass->templ->parse_template("users", "move_files_lightbox");
					unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"FILES2MOVE" => $mmhclass->input->get_vars['files'],
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				);
				
				exit($mmhclass->templ->parse_template("users", "move_files_lightbox"));
			}
			break;
		case "move_files-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['031'];
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['files']) == true) {
				$mmhclass->templ->error($mmhclass->lang['013'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['move_to']) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['move_to'], $mmhclass->info->user_data['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1 && $mmhclass->input->post_vars['move_to'] !== "root") {
					$mmhclass->templ->error($mmhclass->lang['949'], true);
				} else {
					$files2move = $mmhclass->image->basename(explode(",", $mmhclass->input->post_vars['files']));
					
					foreach ($files2move as $id => $filename) {
						if ($mmhclass->funcs->is_null($filename) == true) {
							$mmhclass->templ->error($mmhclass->lang['023'], true);
						} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false) {
							$mmhclass->templ->error(sprintf($mmhclass->lang['024'], $filename), true);
						} else {
							$mmhclass->db->query("UPDATE `[1]` SET `album_id` = '[2]' WHERE `filename` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->input->post_vars['move_to'], $filename));
						}
					}
					
					$mmhclass->templ->message(sprintf($mmhclass->lang['032'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $mmhclass->input->post_vars['move_to']), true);
				}
			}
			break;
		case "delete_files":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$files2delete = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
				foreach ($files2delete as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['023']));
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false && $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' AND `gallery_id` = '[3]' ;", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->info->user_data['user_id']))) === 0) {
						exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['024'], $filename)));
					}
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"FILES2DELETE" => $mmhclass->input->get_vars['files'],
					"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				);
				
				exit($mmhclass->templ->parse_template("users", "delete_files_lightbox"));
			}
			break;
		case "delete_files-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['026'];
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['files']) == true) {
				$mmhclass->templ->error($mmhclass->lang['013'], true);
			} else {
				$files2delete = $mmhclass->image->basename(explode(",", $mmhclass->input->post_vars['files']));
			
				foreach ($files2delete as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						$mmhclass->templ->error($mmhclass->lang['023'], true);
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false && $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' AND `gallery_id` = '[3]' ;", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->info->user_data['user_id']))) === 0) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['024'], $filename), true);
					} else {
						if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == true){
						if (unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$filename) == false) {
							$mmhclass->templ->error(sprintf($mmhclass->lang['027'], $filename), true);
						}
			
						if ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).($thumbnail = $mmhclass->image->thumbnail_name($filename))) == true) {
							if (unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$thumbnail) == false) {
								$mmhclass->templ->error(sprintf($mmhclass->lang['028'], $filename), true);
							}
						}
						}
						$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $filename));
						$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename));
					}
				}
				$mmhclass->templ->message(sprintf($mmhclass->lang['029'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return'])), true);
			}
			break;

		case "albums-c":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				 );
				
				exit($mmhclass->templ->parse_template("users", "new_album_lightbox"));
			}
			break;
		case "albums-c-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['020'];
			
			$album_title = preg_replace('/[^\w\._]+/', '_', $mmhclass->input->post_vars['album_title']);
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($album_title) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $mmhclass->info->user_data['user_id']))) == 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['022'], $album_title), true);
			} elseif (isset($mmhclass->input->post_vars['enablepw']) && strlen($mmhclass->input->post_vars['album_password'])  < 2) {
				$mmhclass->templ->error($mmhclass->lang['800'], true);
			}else {
			if (isset($mmhclass->input->post_vars['enablepw']) && strlen($mmhclass->input->post_vars['album_password']) > 2) {
					$mmhclass->db->query("INSERT INTO `[1]` (`album_title`, `gallery_id`,`password`, `is_private`, `has_pw`) VALUES ('[2]', '[3]','[4]','[5]', '[6]');", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $mmhclass->info->user_data['user_id'],md5($mmhclass->input->post_vars['album_password']), $mmhclass->input->post_vars['is_private'], 1));
			}else {$mmhclass->db->query("INSERT INTO `[1]` (`album_title`, `gallery_id`, `is_private`) VALUES ('[2]', '[3]','[4]');", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $mmhclass->info->user_data['user_id'],$mmhclass->input->post_vars['is_private']));
				}
				$newalbum = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $mmhclass->info->user_data['user_id'])));
				
				$mmhclass->templ->message(sprintf($mmhclass->lang['021'], $album_title, (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $newalbum['album_id']), true);
			}
			break;
			
		case "albums-r":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->get_vars['album'], $mmhclass->info->user_data['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['017']));
				} else {
					$oldalbum = $mmhclass->db->fetch_array($sql);
					
					$mmhclass->templ->templ_vars[] = array(
						"ALBUM_ID" => $oldalbum['album_id'],
						"OLD_TITLE" => $oldalbum['album_title'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
						"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
						"IS_PRIVATE" => (($oldalbum['is_private'] == '1') ?  "checked=\"checked\"" : NULL),
						"IS_NOT_PRIVATE" => (($oldalbum['is_private'] == '0') ?  "checked=\"checked\"" : NULL),
						"HAS_PASSWORD" => (($oldalbum['has_pw'] == '1') ?  "checked=\"checked\"" : NULL),
					);
					
					exit($mmhclass->templ->parse_template("users", "rename_album_lightbox"));
				}
			}
			break;
		case "albums-r-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['018'];
			
			$album_title = preg_replace('/[^\w\._]+/', '_', $mmhclass->input->post_vars['album_title']);
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['album']) == true) {
				$mmhclass->templ->error($mmhclass->lang['013'], true);
			} elseif ($mmhclass->funcs->is_null($album_title) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $mmhclass->info->user_data['user_id']))) > 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['022'], $album_title), true);
			}elseif ($mmhclass->db->total_rows(($albumsql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $mmhclass->info->user_data['user_id'])))) !== 1) {
					$mmhclass->templ->error($mmhclass->lang['017'], true);
			}elseif (isset($mmhclass->input->post_vars['enablepw']) && strlen($mmhclass->input->post_vars['album_password'])  < 2 && strlen($mmhclass->input->post_vars['state']) < 1) {
				$mmhclass->templ->error($mmhclass->lang['800'], true);
			} else {
					$oldalbum = $mmhclass->db->fetch_array($albumsql);
					if (isset($mmhclass->input->post_vars['enablepw']) && strlen($mmhclass->input->post_vars['album_password'])  < 1 && $oldalbum['has_pw'] == 1 ) {
						$mmhclass->db->query("UPDATE `[1]` SET `album_title` = '[2]'  , `is_private` = '[4]' WHERE `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $oldalbum['album_id'], $mmhclass->input->post_vars['is_private'] ));
					}elseif(!isset($mmhclass->input->post_vars['enablepw']) && $oldalbum['has_pw'] == 1) {
						$mmhclass->db->query("UPDATE `[1]` SET `album_title` = '[2]'  , `is_private` = '[4]' , `has_pw` = '[5]' , `password` = '[6]' WHERE `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $oldalbum['album_id'], $mmhclass->input->post_vars['is_private'],0,''));
					}elseif(isset($mmhclass->input->post_vars['enablepw']) && strlen($mmhclass->input->post_vars['album_password'])  > 1) {
						$mmhclass->db->query("UPDATE `[1]` SET `album_title` = '[2]' , `is_private` = '[4]' , `has_pw` = '[5]' , `password` = '[6]' WHERE `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $oldalbum['album_id'], $mmhclass->input->post_vars['is_private'],1,md5($mmhclass->input->post_vars['album_password'])));
					}else{
					
					$mmhclass->db->query("UPDATE `[1]` SET `album_title` = '[2]' , `is_private` = '[4]' WHERE `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $oldalbum['album_id'], $mmhclass->input->post_vars['is_private']));
					}
					$mmhclass->templ->message(sprintf($mmhclass->lang['019'], $album_title, (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $oldalbum['album_id']), true);
				}
			break;
		case "albums-d":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				if ($mmhclass->db->total_rows(($albumsql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->get_vars['album'], $mmhclass->info->user_data['user_id'])))) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['014']));
				} else {
					$oldalbum = $mmhclass->db->fetch_array($albumsql);
					
					$mmhclass->templ->templ_vars[] = array(
						"ALBUM2DELETE" => $oldalbum['album_id'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					);
					
					exit($mmhclass->templ->parse_template("users", "delete_album_lightbox"));
				}
			}
			break;
		case "albums-d-d":
			$mmhclass->templ->page_title .= $mmhclass->lang['015'];
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['album']) == true) {
				$mmhclass->templ->error($mmhclass->lang['013'], true);
			} elseif ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $mmhclass->info->user_data['user_id']))) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['014'], true);
			} else {
				$mmhclass->db->query("DELETE FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id`  = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $mmhclass->info->user_data['user_id']));
				$mmhclass->db->query("UPDATE `[1]` SET `album_id` = '0' WHERE `album_id` = '[2]' AND `gallery_id`  = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->input->post_vars['album'], $mmhclass->info->user_data['user_id']));
			
				$mmhclass->templ->message($mmhclass->lang['016'], true);
			}
			break;	
		case "settings": case "my-settings":
			$mmhclass->templ->page_title .= sprintf($mmhclass->lang['003'], $mmhclass->info->user_data['username']);
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} else {
				$mmhclass->templ->templ_vars[] = array(
				   	"USER_ID" => $mmhclass->info->user_data['user_id'],
				   	"USERNAME" => $mmhclass->info->user_data['username'],
				   	"IP_ADDRESS" => $mmhclass->info->user_data['ip_address'],
				   	"EMAIL_ADDRESS" => $mmhclass->info->user_data['email_address'],
					"IP_HOSTNAME" => gethostbyaddr($mmhclass->info->user_data['ip_address']),
				   	"TIME_JOINED" => date($mmhclass->info->config['date_format'], $mmhclass->info->user_data['time_joined']),
				   	"BOXED_UPLOAD_YES" => (($mmhclass->info->user_data['upload_type'] == "boxed") ? "checked=\"checked\"" : NULL),
				   	"PRIVATE_GALLERY_NO" => (($mmhclass->info->user_data['private_gallery'] == 0) ? "checked=\"checked\"" : NULL),
				   	"PRIVATE_GALLERY_YES" => (($mmhclass->info->user_data['private_gallery'] == 1) ? "checked=\"checked\"" : NULL),
				   	"STANDARD_UPLOAD_YES" => (($mmhclass->info->user_data['upload_type'] == "standard") ? "checked=\"checked\"" : NULL),
					"EMAIL_NOONE" => (($mmhclass->info->user_data['email_visible'] == 0) ? "checked=\"checked\"" : NULL),
					"EMAIL_EVERYONE" => (($mmhclass->info->user_data['email_visible'] == 1) ? "checked=\"checked\"" : NULL),
					"EMAIL_FRIENDS" => (($mmhclass->info->user_data['email_visible'] == 2) ? "checked=\"checked\"" : NULL),
					"HOMEPAGE_NOONE" => (($mmhclass->info->user_data['homepage_visible'] == 0) ? "checked=\"checked\"" : NULL),
					"HOMEPAGE_EVERYONE" => (($mmhclass->info->user_data['homepage_visible'] == 1) ? "checked=\"checked\"" : NULL),
					"HOMEPAGE_FRIENDS" => (($mmhclass->info->user_data['homepage_visible'] == 2) ? "checked=\"checked\"" : NULL),
					"COUNTRY_NOONE" => (($mmhclass->info->user_data['country_visible'] == 0) ? "checked=\"checked\"" : NULL),
					"COUNTRY_EVERYONE" => (($mmhclass->info->user_data['country_visible'] == 1) ? "checked=\"checked\"" : NULL),
					"COUNTRY_FRIENDS" => (($mmhclass->info->user_data['country_visible'] == 2) ? "checked=\"checked\"" : NULL),
					"CITY_NOONE" => (($mmhclass->info->user_data['city_visible'] == 0) ? "checked=\"checked\"" : NULL),
					"CITY_EVERYONE" => (($mmhclass->info->user_data['city_visible'] == 1) ? "checked=\"checked\"" : NULL),
					"CITY_FRIENDS" => (($mmhclass->info->user_data['city_visible'] == 2) ? "checked=\"checked\"" : NULL),
					"FACEBOOK_NOONE" => (($mmhclass->info->user_data['facebook_visible'] == 0) ? "checked=\"checked\"" : NULL),
					"FACEBOOK_EVERYONE" => (($mmhclass->info->user_data['facebook_visible'] == 1) ? "checked=\"checked\"" : NULL),
					"FACEBOOK_FRIENDS" => (($mmhclass->info->user_data['facebook_visible'] == 2) ? "checked=\"checked\"" : NULL),
					"FROM_COUNTRY" => $mmhclass->info->user_data['country'],
					"FROM_CITY" => $mmhclass->info->user_data['city'],
					"HOMEPAGE_URL" => $mmhclass->info->user_data['homepage'],
					"FACEBOOK_PAGE" => $mmhclass->info->user_data['facebook'],
				   	"USER_GROUP" => ((strpos($mmhclass->info->user_data['user_group'], "admin") == true) ? (($mmhclass->info->is_root == false) ? $mmhclass->lang['010'] : $mmhclass->lang['012']) : $mmhclass->lang['011']),
				);
				
				$mmhclass->templ->output("users", "user_settings_page");
			}
			break;
		case "settings-s":
			$mmhclass->templ->page_title .= sprintf($mmhclass->lang['003'], $mmhclass->info->user_data['username']);
			
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true) {
				$mmhclass->templ->error($mmhclass->lang['004'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['005'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == false && strlen($mmhclass->input->post_vars['password']) < 6 || strlen($mmhclass->input->post_vars['password']) > 30) {
				$mmhclass->templ->error($mmhclass->lang['006'], true);
			} elseif (strtolower($mmhclass->input->post_vars['email_address']) !== $mmhclass->info->user_data['email_address'] && $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `email_address` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, strtolower($mmhclass->input->post_vars['email_address'])))) == 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['007'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} else {
				$mmhclass->db->query("UPDATE `[1]` SET `email_address` = '[2]', `private_gallery` = '[3]', `upload_type` = '[4]', `country` = '[6]', `city` = '[7]', `homepage` = '[8]', `facebook` = '[9]', `email_visible` = '[10]', `country_visible` = '[11]', `city_visible` = '[12]', `homepage_visible` = '[13]', `facebook_visible` = '[14]'  WHERE `user_id` = '[5]';", array(MYSQL_USER_INFO_TABLE, strtolower($mmhclass->input->post_vars['email_address']), $mmhclass->input->post_vars['private_gallery'], $mmhclass->input->post_vars['upload_type'], $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['from_country'], $mmhclass->input->post_vars['from_city'], $mmhclass->input->post_vars['homepage_url'],$mmhclass->input->post_vars['facebook_page'],$mmhclass->input->post_vars['email_visible'],$mmhclass->input->post_vars['country_visible'],$mmhclass->input->post_vars['city_visible'],$mmhclass->input->post_vars['homepage_visible'],$mmhclass->input->post_vars['facebook_visible'],));
			
				if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == false && $mmhclass->input->post_vars['password'] !== "*************") {
					$mmhclass->db->query("UPDATE `[1]` SET `password` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, md5($mmhclass->input->post_vars['password']), $mmhclass->info->user_data['user_id']));
				}
				
				$mmhclass->templ->message($mmhclass->lang['008'], true);
			}
			break;
		case "make_ext_gal_files":
			if ($mmhclass->info->is_user == false) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['002']));
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['013']));
			} else {
				$files2gal = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
				foreach ($files2gal as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['023']));
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false) {
						exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['024'], $filename)));
					}
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"FILES2GALERIZE" => $mmhclass->input->get_vars['files'],
					"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				);
				
				exit($mmhclass->templ->parse_template("users", "ext_gallery_lightbox"));
			}
			break;
		case "do_ext_gal":
			$mmhclass->templ->page_title .= $mmhclass->lang['026'];
			if ($mmhclass->info->is_user == false) {
				$mmhclass->templ->error($mmhclass->lang['002'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['files']) == true) {
				$mmhclass->templ->error($mmhclass->lang['013'], true);
			} else {
				$galimages = $mmhclass->image->basename(explode(",", $mmhclass->input->post_vars['files']));
				$gal_layout = '<br /><div><table class="gal_tab"><tr>';
				if ($mmhclass->input->post_vars['design'] != 'left') {
				$gal_layout .= '<td style="padding:5px;"><table class="gal_pict_tab"><tr><td><div class="gal_div"><center><img class="gal_imgs" id="gal_start_img" src=\''.$mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $galimages[0]).$galimages[0].'\'  align="middle" /></center></div></td></tr></table></td>';
				}
				if ($mmhclass->input->post_vars['design'] == 'bottom') {
				$gal_layout .= '</tr><tr>';
				}
				$gal_layout .= '<td><table><tr>';
				
				
				$i = 1;
				foreach ($galimages as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['023']));
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $mmhclass->info->user_data['user_id']) == false) {
						exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['024'], $filename)));
					}
				$file_link = $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$filename;
				$thumb_link = $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$mmhclass->image->thumbnail_name($filename);
				$gal_layout .= '<td><a class="thumbnail" onMouseOver="document.getElementById(\'gal_start_img\').src=\''.$file_link.'\' " href=\''.$file_link.'\' target="_new"><img src=\''.$thumb_link.'\'  border="0" align="top"/><br/>XXL Pic</a></td>';
				if (count($galimages) > 5 && $i % 2 == 0 && $mmhclass->input->post_vars['design'] != 'bottom') {
					$gal_layout .= '</tr><tr>';
				}
				if (count($galimages) > 5 && $i % 5 == 0 && $mmhclass->input->post_vars['design'] == 'bottom') {
					$gal_layout .= '</tr><tr>';
				}
				if (count($galimages) <= 5 && $mmhclass->input->post_vars['design'] != 'bottom') {
					$gal_layout .= '</tr><tr>';
				}
				$i++;
				}
				$gal_layout .= '</tr></table></td>';
			
				if ($mmhclass->input->post_vars['design'] == 'left') {
				$gal_layout .= '<td style="padding:5px;"><table class="gal_pict_tab"><tr><td><div class="gal_div"><center><img class="gal_imgs" id="gal_start_img" src=\''.$mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $galimages[0]).$galimages[0].'\'  align="middle" /></center></div></td></tr></table></td>';
				}
				$gal_layout .= '</tr></table></div><!-- END GALLERY CODE -->';
				
				switch ($mmhclass->input->post_vars['color']) {
				 case "grey":
					$color_var1 = 'gradient_grey.jpg';
					break;
				case "green":
					$color_var1 = 'gradient_green.jpg';
					break;
				case "blue":
					$color_var1 = 'gradient_blue.jpg';
					break;
				case "black":
					$color_var1 = 'gradient_black.jpg';
					break;
				case "red":
					$color_var1 = 'gradient_red.jpg';
					break;	
				case "yellow":
					$color_var1 = 'gradient_yellow.jpg';
					break;
				default:
					$color_var1 = 'gradient_grey.jpg';
				}
				$mmhclass->templ->templ_vars[] = array(
					"GAL_CSS" => '<!-- BEGINN GALLERY CODE --><style type="text/css">.thumbnail {font:12px arial,sans-serif;text-align: center;}.thumbnail img{border: 1px solid white;margin: 0 5px 5px 0;max-width: 100px;max-height: 80px;}.thumbnail:hover img{border: 1px solid blue;}.gal_tab{border: 1px solid;background-image:url('.$mmhclass->info->base_url.'css/images/gradients/'.$color_var1.');}.gal_pict_tab{border-width:1px;border-spacing:2px;border-style:outset;border-color:gray;border-collapse:separate;}.gal_div{height:400px;width:600px;}.gal_imgs{max-height:385px;max-width:585px;}</style>',
					"EXT_GAL_LAYOUT" => $gal_layout,
					"RETURN_URL" =>urldecode($mmhclass->input->post_vars['return']),
				);
				
				exit($mmhclass->templ->output("users", "ext_gallery_done"));
			}
			break;
				
		default: 
			$mmhclass->templ->error($mmhclass->lang['009'], true);
	}
	
?>
