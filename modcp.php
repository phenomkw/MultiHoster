<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.3
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// Moderator Control Panel (c) by TheKPM 2011
	// ======================================== /
	
	require_once "./source/includes/data.php";
	
	require_once "{$mmhclass->info->root_path}source/language/modcp.php";
	
	
	$mmhclass->templ->page_header = $mmhclass->templ->parse_template("modcp/page_header");
	
	$mmhclass->templ->templ_vars[] = array("VERSION" => $mmhclass->info->version);
	$mmhclass->templ->page_footer = $mmhclass->templ->parse_template("modcp/page_footer");
	
	if ($mmhclass->info->is_mod == false && $mmhclass->info->is_admin == false) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);	
	}
	
	switch ($mmhclass->input->get_vars['act']) {
		case "delete_files":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$files2delete = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
				
				foreach ($files2delete as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['530']));
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == false && $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename))) === 0) {
						exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['843'], $filename)));
					}
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"FILES2DELETE" => $mmhclass->input->get_vars['files'],
					"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
				);
				
				exit($mmhclass->templ->parse_template("modcp/modcp", "delete_files_lightbox"));
			}
		case "delete_files-d":
			$file_list = $mmhclass->image->basename(explode(",", (($mmhclass->funcs->is_null($mmhclass->input->get_vars['d']) == false) ? $mmhclass->input->get_vars['files'] : $mmhclass->input->post_vars['files'])));
			if ($mmhclass->funcs->is_null($file_list) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				foreach ($file_list as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						$mmhclass->templ->error($mmhclass->lang['530'], true);
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == false && $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename))) === 0) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['843'], $filename), true);
					} else {
						if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == true) {
						if (unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$filename) == false) {
							$mmhclass->templ->error(sprintf($mmhclass->lang['460'], $filename), true);
						}
	
						if ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).($thumbnail = $mmhclass->image->thumbnail_name($filename))) == true) {
							if (unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename).$thumbnail) == false) {
								$mmhclass->templ->error(sprintf($mmhclass->lang['687'], $filename), true);
							}
						}
						}
						$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $filename));
						$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename));
					}
				}
				$mmhclass->templ->message(sprintf($mmhclass->lang['565'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return'])), true);
			}
			break;
		
		case "ban_control":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '2';", array(MYSQL_BAN_FILTER_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop']['banned_user_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"BAN_ID" => $row['ban_id'],
					"USERNAME" => $row['ban_value'],
				);
				
				$mmhclass->templ->templ_globals['banned_user_whileloop'] .= $mmhclass->templ->parse_template("modcp/modcp", "ban_control_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);       
			}
		
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '1';", array(MYSQL_BAN_FILTER_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop']['banned_ip_address_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"BAN_ID" => $row['ban_id'],
					"IP_ADDRESS" => ((($hostname = gethostbyaddr($row['ban_value'])) && $hostname !== $row['ban_value']) ? sprintf("%s (%s)", $row['ban_value'], $hostname) : $row['ban_value']),
				);
				
				$mmhclass->templ->templ_globals['banned_ip_address_whileloop'] .= $mmhclass->templ->parse_template("modcp/modcp", "ban_control_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $hostname);       
			}
			
			$mmhclass->templ->output("modcp/modcp", "ban_control_page");
			break;
		case "ban_control-u":

			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['do_ban']['username']) == false) {

				if ($mmhclass->db->total_rows(($user_data = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` = '[2]' AND `user_group` = 'normal_user' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['do_ban']['username'])))) == 1) {

					if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '2' AND `ban_value` = '[2]' LIMIT 1;", array(MYSQL_BAN_FILTER_TABLE, $mmhclass->input->post_vars['do_ban']['username']))) !== 1) {

						$mmhclass->db->query("INSERT INTO `[1]` (`ban_type`, `time_banned`, `ban_value`) VALUES ('2', '[2]', '[3]');", array(MYSQL_BAN_FILTER_TABLE, time(), $mmhclass->input->post_vars['do_ban']['username']));

						

						if ($mmhclass->input->post_vars['delete_files']['username'] == 1) {

							$user_data = $mmhclass->db->fetch_array($user_data);

							

							$files2delete = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $user_data['user_id']));

							

							while ($row = $mmhclass->db->fetch_array($files2delete)) {

								if ($mmhclass->funcs->is_file($row['filename'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']), true) == true) {

									unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$row['filename']);

								}

									if ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).($thumbnail = $mmhclass->image->thumbnail_name($row['filename']))) == true) {

										unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$thumbnail);

									}


									$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $row['filename']));

									$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']));

								

							}

						}

					}

				}

			}
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['do_ban']['ip_address']) == false) {

				if (preg_match("#^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3}|\*)\.([0-9]{1,3}|\*)$#", $mmhclass->input->post_vars['do_ban']['ip_address']) == true) {

					if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '1' AND `ban_value` = '[2]' LIMIT 1;", array(MYSQL_BAN_FILTER_TABLE, $mmhclass->input->post_vars['do_ban']['ip_address']))) !== 1) {

						if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ip_address` = '[2]' AND (`user_group` = 'root_admin' OR `user_group` = 'normal_admin') LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['do_ban']['ip_address']))) !== 1) {

							$mmhclass->db->query("INSERT INTO `[1]` (`ban_type`, `time_banned`, `ban_value`) VALUES ('1', '[2]', '[3]');", array(MYSQL_BAN_FILTER_TABLE, time(), $mmhclass->input->post_vars['do_ban']['ip_address']));

						

							if (strpos($mmhclass->input->post_vars['do_ban']['ip_address'], "*") === false) {

								if ($mmhclass->input->post_vars['delete_users']['ip_address'] == 1) {

									$mmhclass->db->query("DELETE FROM `[1]` WHERE `ip_address` = '[2]';", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['do_ban']['ip_address']));

								}

								

								if ($mmhclass->input->post_vars['delete_files']['ip_address'] == 1) {
									$files2delete = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `ip_address` = '[2]';", array(MYSQL_FILE_LOGS_TABLE, $mmhclass->input->post_vars['do_ban']['ip_address']));
									while ($row = $mmhclass->db->fetch_array($files2delete)) {
										if ($mmhclass->funcs->is_file($row['filename'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']), true) == true) {
											unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$row['filename']);

											if ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).($thumbnail = $mmhclass->image->thumbnail_name($row['filename']))) == true) {
												unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$thumbnail);
											}
										}
										$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $row['filename']));
										$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']));
										
									}
								}
							}
						}
					}
				}
			}
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['unban']['username']) == false) {
				foreach ($mmhclass->input->post_vars['unban']['username'] as $ban_id) {
					$mmhclass->db->query("DELETE FROM `[1]` WHERE `ban_id` = '[2]' AND `ban_type` = '2';", array(MYSQL_BAN_FILTER_TABLE, $ban_id));
				}
			}
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['unban']['ip_address']) == false) {
				foreach ($mmhclass->input->post_vars['unban']['ip_address'] as $ban_id) {
					$mmhclass->db->query("DELETE FROM `[1]` WHERE `ban_id` = '[2]' AND `ban_type` = '1';", array(MYSQL_BAN_FILTER_TABLE, $ban_id));
				}
			}
			
			$mmhclass->templ->message($mmhclass->lang['317'], true);
			break;
			
		case "user_list":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `user_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_USER_INFO_TABLE));
		
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"USER_ID" => $row['user_id'],
					"USERNAME" => $row['username'],
					"IP_ADDRESS" => 'Admins only!',
					"EMAIL_ADDRESS" => $row['email_address'],
					"IP_HOSTNAME" => 'Admins only!',
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					"TIME_JOINED" => date($mmhclass->info->config['date_format'], $row['time_joined']),
					"GALLERY_STATUS" => (($row['private_gallery'] == 1) ? $mmhclass->lang['481'] : $mmhclass->lang['646']),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['user_id'])))),
				);
				
				$mmhclass->templ->templ_globals['user_list_whileloop'] .= $mmhclass->templ->parse_template("modcp/modcp", "user_list_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
			}
			
			$mmhclass->templ->templ_vars[] = array("PAGINATION_LINKS" => $mmhclass->templ->pagelinks("modcp.php?act=user_list", $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_USER_INFO_TABLE)))));
			
			$mmhclass->templ->output("modcp/modcp", "user_list_page");
			break;
		case "users-s":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
			
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);	
			} elseif ($mmhclass->db->total_rows($sql) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['278'], true);
			} else {
				$user_data = $mmhclass->db->fetch_array($sql);
				
				if ($user_data['user_group'] === "root_admin" && $mmhclass->info->is_root == false) {
					$mmhclass->templ->error($mmhclass->lang['772'], true);
				} else {
					$mmhclass->templ->templ_globals['is_root'] = (($user_data['user_group'] === "root_admin") ? true : false);
					
					$mmhclass->templ->templ_vars[] = array(
						"USER_ID" => $user_data['user_id'],
						"USERNAME" => $user_data['username'],
						"IP_ADDRESS" => 'Admins only!',  
						"EMAIL_ADDRESS" => $user_data['email_address'],
						"IP_HOSTNAME" => 'Admins only!',
						"TIME_JOINED" => date($mmhclass->info->config['date_format'], $user_data['time_joined']),
						"BOXED_UPLOAD_YES" => (($user_data['upload_type'] == "boxed") ? "checked=\"checked\"" : NULL),
						"PRIVATE_GALLERY_NO" => (($user_data['private_gallery'] == 0) ? "checked=\"checked\"" : NULL),
						"PRIVATE_GALLERY_YES" => (($user_data['private_gallery'] == 1) ? "checked=\"checked\"" : NULL),
						"STANDARD_UPLOAD_YES" => (($user_data['upload_type'] == "standard") ? "checked=\"checked\"" : NULL),
						"ACCOUNT_COUNT" => $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ip_address` = '[2]';", array(MYSQL_USER_INFO_TABLE, $user_data['ip_address']))),
					);
					
					$mmhclass->templ->output("modcp/modcp", "user_settings_page");
				}
			}
			break;
		case "users-s-s":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['user_id']));
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['user_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);	
			} elseif ($mmhclass->db->total_rows($sql) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['278'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true) {
				$mmhclass->templ->error($mmhclass->lang['362'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['112'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == false && strlen($mmhclass->input->post_vars['password']) < 6 || strlen($mmhclass->input->post_vars['password']) > 30) {
				$mmhclass->templ->error($mmhclass->lang['337'], true);
			} else {
				$user_data = $mmhclass->db->fetch_array($sql);
				
				if ($user_data['user_group'] === "root_admin" && $mmhclass->info->is_root == false) {
					$mmhclass->templ->error($mmhclass->lang['772'], true);
				} else {
					$mmhclass->db->query("UPDATE `[1]` SET `email_address` = '[2]', `private_gallery` = '[3]', `upload_type` = '[4]' WHERE `user_id` = '[5]';", array(MYSQL_USER_INFO_TABLE, strtolower($mmhclass->input->post_vars['email_address']), $mmhclass->input->post_vars['private_gallery'], $mmhclass->input->post_vars['upload_type'], $user_data['user_id']));
					
					if ($mmhclass->info->is_root == true && $user_data['user_group'] !== "root_admin") {
						$mmhclass->db->query("UPDATE `[1]` SET `user_group` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, (($mmhclass->input->post_vars['user_group'] == 1) ? "normal_user" : "normal_admin"), $user_data['user_id']));
					}
					
					if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == false && $mmhclass->input->post_vars['password'] !== "*************") {
						$mmhclass->db->query("UPDATE `[1]` SET `password` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, md5($mmhclass->input->post_vars['password']), $user_data['user_id']));
					}
					
					$mmhclass->templ->message($mmhclass->lang['330'], true);
				}
			}
			break;
		case "rename_file_title":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['file']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} elseif ($mmhclass->funcs->is_file($mmhclass->input->get_vars['file'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']), true) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['843'], $mmhclass->image->basename($mmhclass->input->get_vars['file'])), true);
			} else {			
				$new_title = htmlentities($mmhclass->input->get_vars['title']);
				
				$mmhclass->db->query("UPDATE `[1]` SET `file_title` = '[2]' WHERE `filename` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $new_title, $mmhclass->image->basename($mmhclass->input->get_vars['file'])));
				
				exit($new_title);
			}
			break;
		case "move_files":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['278']));
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
				
					$files2move = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
					
					foreach ($files2move as $id => $filename) {
						if ($mmhclass->funcs->is_null($filename) == true) {
							exit($mmhclass->templ->lightbox_error($mmhclass->lang['530']));
						} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $user_data['user_id']) == false) {
							exit($mmhclass->templ->lightbox_error(sprintf($mmhclass->lang['843'], $filename)));
						} 
					}
					
					$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $user_data['user_id']));
				
					while ($row = $mmhclass->db->fetch_array($sql)) {
						$mmhclass->templ->templ_globals['get_whileloop'] = true;
						
						$mmhclass->templ->templ_vars[] = array(
							"ALBUM_ID" => $row['album_id'],
							"ALBUM_NAME" => $row['album_title'],
						);
						
						$mmhclass->templ->templ_globals['album_options_whileloop'] .= $mmhclass->templ->parse_template("modcp/modcp", "move_files_lightbox");
						unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
					}
					
					$mmhclass->templ->templ_vars[] = array(
						"USER_ID" => $user_data['user_id'],
						"FILES2MOVE" => $mmhclass->input->get_vars['files'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
						"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
					);
					
					exit($mmhclass->templ->parse_template("modcp/modcp", "move_files_lightbox"));
				}
			}
			break;
		case "move_files-d":
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['move_to']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['user_id']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['files']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					$mmhclass->templ->error($mmhclass->lang['278'], true);
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
						
					$files2move = $mmhclass->image->basename(explode(",", $mmhclass->input->post_vars['files']));
					
					foreach ($files2move as $id => $filename) {
						if ($mmhclass->funcs->is_null($filename) == true) {
							$mmhclass->templ->error($mmhclass->lang['530'], true);
						} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true, $user_data['user_id']) == false) {
							$mmhclass->templ->error(sprintf($mmhclass->lang['843'], $filename), true);
						} else {
							$mmhclass->db->query("UPDATE `[1]` SET `album_id` = '[2]' WHERE `filename` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->input->post_vars['move_to'], $filename));
						}
					}
					
					$mmhclass->templ->message(sprintf($mmhclass->lang['413'], (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $user_data['user_id'], $mmhclass->input->post_vars['move_to']), true);
				}
			}
			break;
		case "albums-c":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['278']));
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					$mmhclass->templ->templ_vars[] = array(
						"USER_ID" => $user_data['user_id'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
						"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
					 );
					
					exit($mmhclass->templ->parse_template("modcp/modcp", "new_album_lightbox"));
				}
			}
			break;
		case "albums-c-d":
			$album_title = htmlspecialchars($mmhclass->input->post_vars['album_title']);
			
			if ($mmhclass->funcs->is_null($album_title) == true) {
				$mmhclass->templ->error($mmhclass->lang['362'], true);
			} elseif ($mmhclass->funcs->is_null($mmhclass->input->post_vars['user_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					$mmhclass->templ->error($mmhclass->lang['278'], true);
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $user_data['user_id']))) == 1) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['746'], $album_title), true);
					} else {
						$mmhclass->db->query("INSERT INTO `[1]` (`album_title`, `gallery_id`) VALUES ('[2]', '[3]');", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $user_data['user_id']));
						
						$newalbum = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $user_data['user_id'])));
						
						$mmhclass->templ->message(sprintf($mmhclass->lang['412'], $album_title, (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $user_data['user_id'], $newalbum['album_id']), true);
					}
				}
			}
			break;
		case "albums-r":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
			
				if ($mmhclass->db->total_rows($sql) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['278']));
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->get_vars['album'], $user_data['user_id']));
					
					if ($mmhclass->db->total_rows($sql) !== 1) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['338']));
					} else {
						$oldalbum = $mmhclass->db->fetch_array($sql);
						
						$mmhclass->templ->templ_vars[] = array(
							"USER_ID" => $user_data['user_id'],
							"ALBUM_ID" => $oldalbum['album_id'],
							"OLD_TITLE" => $oldalbum['album_title'],
							"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
							"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
						);
						
						exit($mmhclass->templ->parse_template("modcp/modcp", "rename_album_lightbox"));
					}
				}
			}
			break;
		case "albums-r-d":
			$album_title = htmlspecialchars($mmhclass->input->post_vars['album_title']);
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['user_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} elseif ($mmhclass->funcs->is_null($album_title) == true) {
				$mmhclass->templ->error($mmhclass->lang['362'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					$mmhclass->templ->error($mmhclass->lang['278'], true);
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_title` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $user_data['user_id']))) == 1) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['746'], $album_title), true);
					} else {
						if ($mmhclass->db->total_rows(($albumsql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $user_data['user_id'])))) !== 1) {
							$mmhclass->templ->error($mmhclass->lang['338'], true);
						} else {
							$oldalbum = $mmhclass->db->fetch_array($albumsql);
							
							$mmhclass->db->query("UPDATE `[1]` SET `album_title` = '[2]' WHERE `album_id` = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $album_title, $oldalbum['album_id']));
							
							$mmhclass->templ->message(sprintf($mmhclass->lang['101'], $oldalbum['album_title'], $album_title, (($mmhclass->funcs->is_null($mmhclass->input->post_vars['return']) == true) ? base64_encode($mmhclass->info->base_url) : $mmhclass->input->post_vars['return']), $user_data['user_id'], $oldalbum['album_id']), true);
						}
					}
				}
			}
			break;
		case "albums-d":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['278']));
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					if ($mmhclass->db->total_rows(($albumsql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->get_vars['album'], $user_data['user_id'])))) !== 1) {
						exit($mmhclass->templ->lightbox_error($mmhclass->lang['442']));
					} else {
						$oldalbum = $mmhclass->db->fetch_array($albumsql);
							
						$mmhclass->templ->templ_vars[] = array(
							"USER_ID" => $user_data['user_id'],
							"ALBUM2DELETE" => $oldalbum['album_id'],
							"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
						);
						
						exit($mmhclass->templ->parse_template("modcp/modcp", "delete_album_lightbox"));
					}
				}
			}
			break;
		case "albums-d-d":
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['album']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['user_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['user_id']));
				
				if ($mmhclass->db->total_rows($sql) !== 1) {
					$mmhclass->templ->error($mmhclass->lang['278'], true);
				} else {
					$user_data = $mmhclass->db->fetch_array($sql);
					
					if ($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id` = '[3]' LIMIT 1;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $user_data['user_id']))) !== 1) {
						$mmhclass->templ->error($mmhclass->lang['442'], true);
					} else {
						$mmhclass->db->query("DELETE FROM `[1]` WHERE `album_id` = '[2]' AND `gallery_id`  = '[3]';", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->input->post_vars['album'], $user_data['user_id']));
						$mmhclass->db->query("UPDATE `[1]` SET `album_id` = '0' WHERE `album_id` = '[2]' AND `gallery_id` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->input->post_vars['album'], $user_data['user_id']));
						
						$mmhclass->templ->message(sprintf($mmhclass->lang['738'], $user_data['user_id']), true);
					}
				}
			}
			break;	
		default:
			$mmhclass->info->selected_album = (int)$mmhclass->input->get_vars['cat'];
			$mmhclass->info->selected_gallery = (int)$mmhclass->input->get_vars['gal'];
			
			$mmhclass->info->user_owned_gallery = (($mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == false) ? true : false);
			$mmhclass->info->gallery_owner_data = (($mmhclass->info->user_owned_gallery == true) ? $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->info->selected_gallery))) : array("user_id" => 0, "username" => $mmhclass->info->config['site_name']));
			
			$mmhclass->info->gallery_url = sprintf("%smodcp.php?gal=%s", $mmhclass->info->base_url, $mmhclass->info->gallery_owner_data['user_id']);
			$mmhclass->info->gallery_url_full = sprintf("%s%s", $mmhclass->info->gallery_url, (($mmhclass->funcs->is_null($mmhclass->info->selected_album) == true) ? NULL : "&amp;cat={$mmhclass->info->selected_album}"));
			
			if ($mmhclass->funcs->is_null($mmhclass->info->gallery_owner_data['user_id']) == true && $mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == false) {
				$mmhclass->templ->error($mmhclass->lang['383'], true);
			} else {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') ORDER BY `file_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search'])));
				
				if ($mmhclass->db->total_rows($sql) < 1) {
					$mmhclass->templ->templ_globals['empty_gallery'] = true;
				} else {
					$mmhclass->templ->templ_globals['file_options'] = true;
					
					while ($row = $mmhclass->db->fetch_array($sql)) {
						$break_line = (($tdcount >= 4) ? true : false);
						$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
						$tdcount++;
						
						$mmhclass->templ->templ_vars[] = array(
							"FILE_ID" => $row['file_id'],
							"FILENAME" => $row['filename'],
							"FILE_TITLE" => $row['file_title'],
							"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
							"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
						);
						
						$gallery_html .= $mmhclass->templ->parse_template("global", "global_gallery_layout");
						unset($break_line, $mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
					}
				}
				
				if ($mmhclass->info->user_owned_gallery == true) {
					$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' LIMIT 50;", array(MYSQL_GALLERY_ALBUMS_TABLE, $mmhclass->info->gallery_owner_data['user_id']));
					
					while ($row = $mmhclass->db->fetch_array($sql)) {
						$mmhclass->templ->templ_globals['get_whileloop'] = true;
						
						if ($mmhclass->info->selected_album == $row['album_id']) {
							$curalbum = $row;
						}
						
						$mmhclass->templ->templ_vars[] = array(
							"ALBUM_ID" => $row['album_id'],
							"ALBUM_NAME" => $row['album_title'],
							"GALLERY_URL" => $mmhclass->info->gallery_url,
							"FULL_GALLERY_URL" => $mmhclass->info->gallery_url_full,
							"RETURN_URL" => base64_encode($mmhclass->info->page_url),
							"GALLERY_ID" => $mmhclass->info->gallery_owner_data['user_id'],
							"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $row['album_id'])))),
						);
						
						$mmhclass->templ->templ_globals['album_pulldown_whileloop'] .= $mmhclass->templ->parse_template("modcp/modcp", "admin_gallery_page");
						unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
					}
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"GALLERY_HTML" => $gallery_html,		
					"GALLERY_URL" => $mmhclass->info->gallery_url,
					"CURRENT_PAGE" => $mmhclass->info->current_page,
					"FULL_GALLERY_URL" => $mmhclass->info->gallery_url_full,
					"RETURN_URL" => base64_encode($mmhclass->info->page_url),
					"GALLERY_ID" => $mmhclass->info->gallery_owner_data['user_id'],
					"IMAGE_SEARCH" => urldecode($mmhclass->input->get_vars['search']),
					"GALLERY_OWNER" => $mmhclass->info->gallery_owner_data['username'],
					"ALBUM_NAME" => (($mmhclass->funcs->is_null($curalbum['album_title']) == true) ? NULL : "&raquo; {$curalbum['album_title']}"),
					"EMPTY_GALLERY" => $mmhclass->templ->message((($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == false) ? $mmhclass->lang['598'] : $mmhclass->lang['463']), false),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'])))),
					"TOTAL_ROOT_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '0';", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'])))),
					"PAGINATION_LINKS" => $mmhclass->templ->pagelinks(sprintf("%s%s", $mmhclass->info->gallery_url_full, (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true) ? NULL : sprintf("&amp;search=%s", urldecode($mmhclass->input->get_vars['search'])))), $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]' AND `album_id` = '[3]' AND (`filename` LIKE '%[4]%' OR `file_title` LIKE '%[4]%') ORDER BY `file_id` DESC;", array(MYSQL_FILE_STORAGE_TABLE, $mmhclass->info->gallery_owner_data['user_id'], $mmhclass->info->selected_album, urldecode($mmhclass->input->get_vars['search']))))),	
				);
				
				$mmhclass->templ->output("modcp/modcp", "admin_gallery_page");
			}
	}

?>