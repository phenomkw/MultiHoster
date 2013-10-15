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
	
	require_once "{$mmhclass->info->root_path}source/language/admin.php";
	
	require_once "{$mmhclass->info->root_path}source/includes/admincp/sysinfo.php";
	require_once "{$mmhclass->info->root_path}source/includes/admincp/filediff.php";
	
	$mmhclass->templ->page_header = $mmhclass->templ->parse_template("admin/page_header");
	
	$mmhclass->templ->templ_vars[] = array("VERSION" => $mmhclass->info->version);
	$mmhclass->templ->page_footer = $mmhclass->templ->parse_template("admin/page_footer");
	
	if ($mmhclass->info->is_admin == false) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);		
	}
	
	switch ($mmhclass->input->get_vars['act']) {
		case "phpinfo":
			phpinfo();
			break;
		case "processes":
			$topinfo = _get_processes();
			
			if ($topinfo == false) {
				$mmhclass->templ->error($mmhclass->lang['577'], true);
			} else {
				$mmhclass->templ->templ_vars[] = array("PROCESSES" => $mmhclass->funcs->string2ascii($topinfo));
				$mmhclass->templ->output("admin/admin", "process_list_page");
			}
			break;
		case "errlogs":
			$error_list = $mmhclass->funcs->read_file(sprintf("%ssource/errorlog/php5/%s.log", $mmhclass->info->root_path, date("m-d-Y")));
			
			if ($mmhclass->funcs->is_null($error_list) == true) {
				$mmhclass->templ->error($mmhclass->lang['899'], true);
			} else {
				$mmhclass->templ->templ_vars[] = array("ERROR_LIST" => $error_list);
				$mmhclass->templ->output("admin/admin", "php_error_logs_page");
			}
			break;
		case "sysinfo":
		
			//
			// This System Information page started out displaying just basic
			// information, and now it displays extensive information about the
			// server that Mihalism Multi Host is running ontop of.
			//
			// Why a normal user would need this type of information is questionable,
			// but it does help tons when debugging errors because the page outputs all
			// constants defined by Mihalism Multi Host and all loaded extensions beside
			// the information about the server. So everything is in one place. 
			//
		
			$cpu_info = _get_cpu_info();
			$memory_info = _get_memory_info();
			$disk_use = _get_diskspace_info();
			$system_name = _get_system_name();
			$uptime_info = _get_uptime_info();
			
			$defconsts = get_defined_constants(true);
	
			$disk_image_width = @floor((($disk_use['used'] / $disk_use['total']) * 100) * 4);
	
			if (is_array($memory_info) == true) {
				$mmhclass->templ->templ_globals['show_ram_usage'] = true;
				$mmhclass->templ->templ_globals['show_swap_usage'] = true;
				
				$ram_image_width = @floor((($memory_info['ram']['used'] / $memory_info['ram']['total']) * 100) * 4);
				$swap_image_width = @floor((($memory_info['swap']['used'] / $memory_info['swap']['total']) * 100) * 4);
			}
			
			if (is_numeric($uptime_info) == true) {
				$mmhclass->templ->templ_globals['show_uptime_info'] = true;
			}
			
			if (is_array($cpu_info) == true) {
				$mmhclass->templ->templ_globals['show_cpu_info'] = true;
				$mmhclass->templ->templ_globals['show_cpu_usage'] = true;
			}
					
			if (is_string($system_name) == true) {
				$mmhclass->templ->templ_globals['show_version_info'] = true;
			}
			
			$mmhclass->templ->templ_vars[] = array(	 
				"UPTIME_TOTAL" => $uptime_info, 
				"VERSION_INFO" => $system_name,
				"SYSTEM_BUILD" => php_uname("a"), 
				"SCRIPT_OWNER_UID" => getmyuid(),
				"CPU_SPEED" => $cpu_info['speed'],
				"CPU_MODEL" => $cpu_info['model'],
				"SCRIPT_OWNER" => get_current_user(),
				"CPU_LOAD_1m" => $cpu_info['load']['0'],
				"CPU_LOAD_5m" => $cpu_info['load']['1'],
				"CPU_LOAD_15m" => $cpu_info['load']['2'],
				"RAM_USAGE_IMAGE_WIDTH_2" => floor(400 - $ram_image_width),
				"DEFINED_CONSTANTS" => var_export($defconsts['user'], true),
				"SWAP_USAGE_IMAGE_WIDTH_2" => floor(400 - $swap_image_width),
				"DISK_USAGE_IMAGE_WIDTH_2" => floor(400 - $disk_image_width),
				"LOADED_EXTENSIONS" => var_export(get_loaded_extensions(), true),
				"RAM_USAGE_IMAGE_WIDTH" => (($ram_image_width < 0) ? 0 : $ram_image_width),
				"DISK_USAGE_IMAGE_WIDTH" => (($disk_image_width < 0) ? 0 : $disk_image_width),
				"SWAP_USAGE_IMAGE_WIDTH" => (($swap_image_width < 0) ? 0 : $swap_image_width),
				"DISK_USAGE_USED_SPACE" => $mmhclass->image->format_filesize($disk_use['used']),
				"DISK_USAGE_TOTAL_SPACE" => $mmhclass->image->format_filesize($disk_use['total']),
				"RAM_USAGE_USED_SPACE" => $mmhclass->image->format_filesize($memory_info['ram']['used']),
				"RAM_USAGE_TOTAL_SPACE" => $mmhclass->image->format_filesize($memory_info['ram']['total']),
				"SWAP_USAGE_USED_SPACE" => $mmhclass->image->format_filesize($memory_info['swap']['used']),
				"SWAP_USAGE_TOTAL_SPACE" => $mmhclass->image->format_filesize($memory_info['swap']['total']),
			);
			
			$mmhclass->templ->output("admin/admin", "system_info_page");
			break;
		case "site_settings":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_SITE_SETTINGS_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$setting[$row['config_key']] = $row['config_value']; 
			}

			for ($i = 1; $i <= 40; $i++) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"MAX_RESULTS_SUM" => $i,
					"MAX_RESULTS_SELECTED" => (($setting['max_results'] == $i) ? "selected=\"selected\"" : NULL),
				);				
				
				$mmhclass->templ->templ_globals['max_results_forloop'] .= $mmhclass->templ->parse_template("admin/admin", "site_settings_page");
				unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
			}
			
			$versioncheck = unserialize($mmhclass->funcs->get_http_content("www.multihosterscript.com/version/version.txt", 1));
		
			$mmhclass->templ->templ_globals['new_version'] = (($versioncheck !== false && version_compare($versioncheck['version'], $mmhclass->info->version, ">") == true) ? true : false);
			
			if (APACHE_IS_AVAILABLE == true) {
				$apache_modules = apache_get_modules();
				
				if (in_array("mod_rewrite", $apache_modules) === false) {
					// Maybe we should actually add something here?
					// A lot of servers are CGI anyways so wont get this far. 
				}
			}
			
			$mmhclass->templ->templ_vars[] = array(	   
				"EMAIL_IN" => $setting['email_in'],
				"EMAIL_OUT" => $setting['email_out'],
				"SITE_NAME" => $setting['site_name'],
				"DATE_FORMAT" => $setting['date_format'],
				"MAX_FILESIZE" => $setting['max_filesize'],
				"FILE_EXTENSIONS" => $setting['file_extensions'],
				"THUMBNAIL_WIDTH" => $setting['thumbnail_width'],
				"THUMBNAIL_HEIGHT" => $setting['thumbnail_height'],
				"RECAPTCHA_PUBLIC" => $setting['recaptcha_public'],
				"GOOGLE_ANALYTICS" => $setting['google_analytics'],
				"VERSIONCHECK_VERSION" => $versioncheck['version'],
				"RECAPTCHA_PRIVATE" => $setting['recaptcha_private'],
				"USER_MAX_FILESIZE" => $setting['user_max_filesize'],
				"VERSIONCHECK_DOWNLOAD" => $versioncheck['download'],
				"VERSIONCHECK_ANNOUNCEMENT" => $versioncheck['infopage'],
				"MAX_BANDWIDTH" => ($setting['max_bandwidth'] / 1048576),
				"USER_FILE_EXTENSIONS" => $setting['user_file_extensions'],
				"CURRENT_TIME" => date($mmhclass->info->config['date_format']),
				"USER_MAX_BANDWIDTH" => ($setting['user_max_bandwidth'] / 1048576),
				"PROXY_IMAGES_NO" => (($setting['proxy_images'] == 0) ? "checked=\"checked\"" : NULL),
				"PROXY_IMAGES_YES" => (($setting['proxy_images'] == 1) ? "checked=\"checked\"" : NULL),
				"GALLERY_VIEWING_NO" => (($setting['gallery_viewing'] == 0) ? "checked=\"checked\"" : NULL),
				"GALLERY_VIEWING_YES" => (($setting['gallery_viewing'] == 1) ? "checked=\"checked\"" : NULL),
				"VERSIONCHECK_PUBDATE" => date($mmhclass->info->config['date_format'], $versioncheck['pubtime']),
				"USERONLY_UPLOADING_NO" => (($setting['useronly_uploading'] == 0) ? "checked=\"checked\"" : NULL),
				"UPLOADING_DISABLED_NO" => (($setting['uploading_disabled'] == 0) ? "checked=\"checked\"" : NULL),
				"THUMBNAIL_TYPE_PNG" => (($setting['thumbnail_type'] == "ping") ? "selected=\"selected\"" : NULL),
				"THUMBNAIL_TYPE_JPEG" => (($setting['thumbnail_type'] == "jpeg") ? "selected=\"selected\"" : NULL),
				"UPLOADING_DISABLED_YES" => (($setting['uploading_disabled'] == 1) ? "checked=\"checked\"" : NULL),
				"USERONLY_UPLOADING_YES" => (($setting['useronly_uploading'] == 1) ? "checked=\"checked\"" : NULL),
				"ADVANCED_THUMBNAILS_NO" => (($setting['advanced_thumbnails'] == 0) ? "checked=\"checked\"" : NULL),
				"ADVANCED_THUMBNAILS_YES" => (($setting['advanced_thumbnails'] == 1) ? "checked=\"checked\"" : NULL),
				"REGISTRATION_DISABLED_NO" => (($setting['registration_disabled'] == 0) ? "checked=\"checked\"" : NULL),
				"REGISTRATION_DISABLED_YES" => (($setting['registration_disabled'] == 1) ? "checked=\"checked\"" : NULL),
		// BEGIN SHORT_URL MOD
				"SHORTENER_NONE" => (($setting['shortener'] == 'none') ? "checked=\"checked\"" : NULL),
				"SHORTENER_GOO.GL" => (($setting['shortener'] == 'goo.gl') ? "checked=\"checked\"" : NULL),
				"SHORTENER_BIT.LY" => (($setting['shortener'] == 'bit.ly') ? "checked=\"checked\"" : NULL),
				"SHORTENER_ADF.LY" => (($setting['shortener'] == 'adf.ly') ? "checked=\"checked\"" : NULL),
				"SHORTENER_API_KEY" => $setting['shortener-api-key'],
				"SHORTENER_BITLY_LOGIN" => $setting['shortener-bitly-login'],
		// END SHORT_URL MOD
		// BEGIN WATERMARK MOD
				"WATERMARK_NO" => (($setting['watermark'] == '0') ? "checked=\"checked\"" : NULL),
				"WATERMARK_YES" => (($setting['watermark'] == '1') ? "checked=\"checked\"" : NULL),
				"WATERMARK_POSITION_TOP_LEFT" => (($setting['watermark_position'] == 'top_left') ? "selected=\"selected\"" : NULL),
				"WATERMARK_POSITION_TOP_RIGHT" => (($setting['watermark_position'] == 'top_right') ? "selected=\"selected\"" : NULL),
				"WATERMARK_POSITION_BOTTOM_LEFT" => (($setting['watermark_position'] == 'bottom_left') ? "selected=\"selected\"" : NULL),
				"WATERMARK_POSITION_BOTTOM_RIGHT" => (($setting['watermark_position'] == 'bottom_right') ? "selected=\"selected\"" : NULL),
		// END WATERMARK MOD
				"HOTLINK_ALLOWED" => (($setting['hotlink'] == '1') ?  "checked=\"checked\"" : NULL),
				"HOTLINK_NOT_ALLOWED" => (($setting['hotlink'] == '0') ?  "checked=\"checked\"" : NULL),
        // BEGIN GUEST_URL MOD
                "GUEST_URL_TEN" => (($setting['guest_url'] == '9') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_NINE" => (($setting['guest_url'] == '8') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_EIGHT" => (($setting['guest_url'] == '7') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_SEVEN" => (($setting['guest_url'] == '6') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_SIX" => (($setting['guest_url'] == '5') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_FIVE" => (($setting['guest_url'] == '4') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_FOUR" => (($setting['guest_url'] == '3') ?  "checked=\"checked\"" : NULL),
                "GUEST_URL_THREE" => (($setting['guest_url'] == '2') ?  "checked=\"checked\"" : NULL),
				"GUEST_URL_TWO" => (($setting['guest_url'] == '1') ?  "checked=\"checked\"" : NULL),
				"GUEST_URL_OFF" => (($setting['guest_url'] == '0') ?  "checked=\"checked\"" : NULL),
	    // END GUEST_URL MOD
        // BEGIN GUEST_RECAPTCHA MOD
				"RECAPTCHA_ALLOWED" => (($setting['recaptcha_guest'] == '1') ?  "checked=\"checked\"" : NULL),
				"RECAPTCHA_NOT_ALLOWED" => (($setting['recaptcha_guest'] == '0') ?  "checked=\"checked\"" : NULL),
	    // END GUEST_RECAPTCHA MOD
        // BEGIN SHOW RANDOM MOD
				"SHOW_RANDOM_YES" => (($setting['show_random'] == '1') ?  "checked=\"checked\"" : NULL),
				"SHOW_RANDOM_NO" => (($setting['show_random'] == '0') ?  "checked=\"checked\"" : NULL),
	    // END SHOW RANDOM MOD
		// BEGIN MENU MOD
		//TO BE ADDED AT A LATER DATE
-        //      "MENU_1" => (($setting['top_menu'] == '1') ?  "checked=\"checked\"" : NULL),
-    //    "MENU_0" => (($setting['top_menu'] == '0') ?  "checked=\"checked\"" : NULL),
-      // END MENU MOD 
				"FB_COMM_YES" => (($setting['facebook_comments'] == '1') ?  "checked=\"checked\"" : NULL),
				"FB_COMM_NO" => (($setting['facebook_comments'] == '0') ?  "checked=\"checked\"" : NULL),
				"USER_MAX_SIMUL_FILES" => $setting['max_user_simul_upload'],
				"GUEST_MAX_SIMUL_FILES" => $setting['max_guest_simul_upload'],
				"USE_REWRITE" => (($setting['seo_urls'] == '1') ?  "checked=\"checked\"" : NULL),
				"DONT_USE_REWRITE" => (($setting['seo_urls'] == '0') ?  "checked=\"checked\"" : NULL),
			);
			
			$mmhclass->templ->output("admin/admin", "site_settings_page");
			break;
		case "site_settings-s":
			$config_values = array(
				"site_name" => $mmhclass->input->post_vars['site_name'],
				"date_format" => $mmhclass->input->post_vars['date_format'],
				"thumbnail_type" => $mmhclass->input->post_vars['thumbnail_type'],
				"max_results" => round($mmhclass->input->post_vars['max_results']),
				"file_extensions" => $mmhclass->input->post_vars['file_extensions'],
				"proxy_images" => round($mmhclass->input->post_vars['proxy_images']),
				"google_analytics" => $mmhclass->input->post_vars['google_analytics'],
				"recaptcha_public" => $mmhclass->input->post_vars['recaptcha_public'],
				"max_filesize"  => round($mmhclass->input->post_vars['max_filesize']),
				"recaptcha_private" => $mmhclass->input->post_vars['recaptcha_private'],
				"gallery_viewing" => round($mmhclass->input->post_vars['gallery_viewing']),
				"thumbnail_width" => round($mmhclass->input->post_vars['thumbnail_width']),
				"thumbnail_height" => round($mmhclass->input->post_vars['thumbnail_height']),
				"user_file_extensions" => $mmhclass->input->post_vars['user_file_extensions'],
				"user_max_filesize" => round($mmhclass->input->post_vars['user_max_filesize']),
				"uploading_disabled" => round($mmhclass->input->post_vars['uploading_disabled']),
				"useronly_uploading" => round($mmhclass->input->post_vars['useronly_uploading']),
				"max_bandwidth" => (round($mmhclass->input->post_vars['max_bandwidth']) * 1048576),
				"registration_disabled" => round($mmhclass->input->post_vars['registration_disabled']),
				"user_max_bandwidth" => (round($mmhclass->input->post_vars['user_max_bandwidth']) * 1048576), 
				"advanced_thumbnails" => (($mmhclass->image->manipulator == "imagick") ? round($mmhclass->input->post_vars['advanced_thumbnails']) : 0),
				"email_in" => strtolower(($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_in']) == false) ? $mmhclass->info->config['email_in'] : $mmhclass->input->post_vars['email_in']),
				"email_out" => strtolower(($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_out']) == false) ? $mmhclass->info->config['email_out'] : $mmhclass->input->post_vars['email_out']),
	   // BEGIN SHORT_URL MOD
				"shortener" => $mmhclass->input->post_vars['shortener'],
				"shortener-api-key" => $mmhclass->input->post_vars['shortener-api-key'],
				"shortener-bitly-login" => $mmhclass->input->post_vars['shortener-bitly-login'],
	   // END SHORT_URL MOD
	   // BEGIN WATERMARK MOD
				"watermark" => $mmhclass->input->post_vars['watermark'],
				"watermark_position" => $mmhclass->input->post_vars['watermark_position'],
       // END WATERMARK MOD
				"hotlink" => $mmhclass->input->post_vars['hotlink'],
       // BEGIN GUEST_URL MOD
                "guest_url" => $mmhclass->input->post_vars['guest_url'],
       // END GUEST_URL MOD
       // BEGIN GUEST_RECAPTCHA MOD
                "recaptcha_guest" => $mmhclass->input->post_vars['recaptcha_guest'],
	   // END GUEST_RECAPTCHA MOD
       // BEGIN SHOW RANDOM MOD
                "show_random" => $mmhclass->input->post_vars['show_random'],
	   // END SHOW RANDOM MOD
       // BEGIN MENU MOD
	   //TO BE ADDED AT A LATER TIME
-       //       "top_menu" => $mmhclass->input->post_vars['top_menu'],
-     // END MENU MOD
				"facebook_comments" => $mmhclass->input->post_vars['facebook_comments'],
				"max_user_simul_upload" => $mmhclass->input->post_vars['user_simul_upload'],
				"max_guest_simul_upload" => $mmhclass->input->post_vars['guest_simul_upload'],
				"seo_urls" => $mmhclass->input->post_vars['url_rewrite'],
				
			);
			
			foreach ($config_values as $config_key => $config_value) {
				if ($mmhclass->funcs->is_null($config_value) == false || $mmhclass->funcs->is_null($config_value) == true && $config_value !== 0) {
					$mmhclass->db->query("UPDATE `[1]` SET `config_value` = '[2]' WHERE `config_key` = '[3]';", array(MYSQL_SITE_SETTINGS_TABLE, $config_value, $config_key));
				}
			}
			
			$section_header = "# Mihalism Multi Host Image Proxy"; // Easier to just put it into a variable
			
			$htafile = $mmhclass->funcs->read_file("{$mmhclass->info->root_path}.htaccess");
			$htafile = preg_replace("/{$section_header}(.*){$section_header}/Usi", NULL, $htafile);
			
			if ($mmhclass->input->post_vars['proxy_images'] == 1) {
				$htafile .= "{$section_header}																												\n";
				$htafile .= "<IfModule mod_rewrite.c>																									    \n";
				$htafile .= "	RewriteEngine On																											\n";
				$htafile .= "	RewriteBase {$mmhclass->info->script_path}																				    \n";
				$htafile .= "	RewriteRule ^images/([a-zA-Z0-9]+)\.([^\s]+)$ {$mmhclass->info->script_path}index.php?module=loadimage&file=$1.$2 [L]		\n";
				$htafile .= "	RewriteRule ^images/([a-zA-Z0-9]+)_thumb\.([^\s]+)$ {$mmhclass->info->script_path}index.php?module=thumbnail&file=$1.$2 [L]	\n";
				$htafile .= "</IfModule>																													\n";
				$htafile .= "{$section_header}																												\n";
			}
			
			$mmhclass->funcs->write_file("{$mmhclass->info->root_path}.htaccess", trim($htafile));
			
			$mmhclass->templ->message($mmhclass->lang['001'], true);
			break;
		case "robot_logs":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `log_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_ROBOT_LOGS_TABLE));
			
			if ($mmhclass->db->total_rows($sql) < 1) {
		 		$mmhclass->templ->message($mmhclass->lang['003'], true);
		 	} else {
				while ($row = $mmhclass->db->fetch_array($sql)) {
					$mmhclass->templ->templ_globals['get_whileloop'] = true;
					
					$robot_data = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `robot_id` = '[2]' LIMIT 1;", array(MYSQL_ROBOT_INFO_TABLE, $row['robot_id'])));
					
					$mmhclass->templ->templ_vars[] = array(
						"LOG_ID" => $row['log_id'],
						"PAGE_INDEXED" => $row['page_indexed'],
						"ROBOT_NAME" => $robot_data['robot_name'],
						"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
						"PAGE_INDEXED_TEXT" => $mmhclass->funcs->shorten_url($row['page_indexed']),
						"DATE_INDEXED" => date($mmhclass->info->config['date_format'], $row['time_indexed']),
						"HTTP_REFERER" => (($mmhclass->funcs->is_null($row['http_referer']) == true) ? $mmhclass->lang['004'] : sprintf("<a href=\"%s\" title=\"%s\">%s</a>", $row['http_referer'], $row['http_referer'], $mmhclass->funcs->shorten_url($row['http_referer']))),
					);
					
					$mmhclass->templ->templ_globals['robot_logs_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "robot_logs_page");
					unset($file_owners_data, $mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $robot_data);	
				}
				
				$mmhclass->templ->templ_vars[] = array("PAGINATION_LINKS" => $mmhclass->templ->pagelinks("admin.php?act=robot_logs", $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `log_id` DESC;", array(MYSQL_ROBOT_LOGS_TABLE)))));
				
				$mmhclass->templ->output("admin/admin", "robot_logs_page");
			}
			break;
		case "robot_logs-de":
			$mmhclass->db->query("TRUNCATE `[1]`;", array(MYSQL_ROBOT_LOGS_TABLE));
			
			$mmhclass->templ->message($mmhclass->lang['005'], true);
			break;
		case "language_settings":
			foreach ($mmhclass->info->language_files as $id => $filename) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"FILE_ID" => $id,
					"FILENAME" => $mmhclass->image->basename($filename),
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					"REAL_PATH" => realpath("{$mmhclass->info->root_path}source/language/{$filename}"),
					"LAST_MODIFICATION" => date($mmhclass->info->config['date_format'], filemtime("{$mmhclass->info->root_path}source/language/{$filename}")),
				);
				
				$mmhclass->templ->templ_globals['language_file_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "language_settings_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);
			}
			
			$mmhclass->templ->output("admin/admin", "language_settings_page");
			break;
		case "language_settings-e":
			if ($mmhclass->funcs->is_language_file($mmhclass->input->get_vars['file']) == false) {
				$mmhclass->templ->error($mmhclass->lang['010'], true);	
			} else {
				$mmhclass->lang = array();
				
				include "{$mmhclass->info->root_path}source/language/{$mmhclass->info->language_files[$mmhclass->input->get_vars['file']]}";
				
				preg_match_all("#([0-9]+) -- ([^\n]+)\n#s", implode("", file("{$mmhclass->info->root_path}source/language/{$mmhclass->info->language_files[$mmhclass->input->get_vars['file']]}")), $index_matches);
				
				foreach ($index_matches['1'] as $arraynum => $langindex) {
					$mmhclass->templ->templ_globals['get_whileloop'] = true;
					
					$mmhclass->templ->templ_vars[] = array(
						"LANGUAGE_INDEX" => $langindex,
						"LANGUAGE_DETAILS" => $index_matches['2'][$arraynum],
						"LANGUAGE_TYPE" => gettype($mmhclass->lang[$langindex]),
						"LANGUAGE_CONTENT" => ((in_array(gettype($mmhclass->lang[$langindex]), array("string", "NULL")) == true) ? str_replace(array("\r\n", "\n", "\r", "<br />"), array(NULL, NULL, NULL, "\n"), stripslashes($mmhclass->lang[$langindex])) : var_export($mmhclass->lang[$langindex], true)),
					);
					
					$mmhclass->templ->templ_globals['edit_language_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "edit_language_page");
					unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"LANGUAGE_FILENAME_ID" => $mmhclass->input->get_vars['file'],
					"LANGUAGE_FILENAME" => $mmhclass->image->basename($mmhclass->info->language_files[$mmhclass->input->get_vars['file']]),
				);
				
				$mmhclass->templ->output("admin/admin", "edit_language_page");
			}
			break;
		case "language_settings-e-s":
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['file_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				if ($mmhclass->funcs->is_language_file($mmhclass->input->post_vars['file_id']) == false) {
					$mmhclass->templ->error($mmhclass->lang['010'], true);	
				} else {
					foreach ($mmhclass->input->post_vars['language']['type'] as $langindex => $langtype) {
						$langsetdivider = ((in_array($langtype, array("string", "NULL")) == true) ? "\"" : NULL);
						$index_value .= "\t\t{$langindex} -- {$mmhclass->input->post_vars['language']['desc'][$langindex]}\n";
						$text_value .= sprintf("\t\$mmhclass->lang['%s'] = %s%s%s;\n", $langindex, $langsetdivider, ((in_array($langtype, array("string", "NULL")) == false) ? $mmhclass->input->post_vars['language']['text'][$langindex] : nl2br(str_replace("\"", "\\\"", stripslashes($mmhclass->input->post_vars['language']['text'][$langindex])))), $langsetdivider);
					}
					
					if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['newlang']['id']) == false) {
						foreach ($mmhclass->input->post_vars['newlang']['id'] as $arraynum => $langindex) {
							if (preg_match("#^([0-9]{1,4})$#", $mmhclass->input->post_vars['newlang']['id'][$arraynum]) == true) {
								$index_value .= "\t\t{$langindex} -- {$mmhclass->input->post_vars['newlang']['desc'][$arraynum]}\n";
								$langsetdivider = ((in_array($mmhclass->input->post_vars['newlang']['type'][$arraynum], array("string", "NULL")) == true) ? "\"" : NULL);
								$text_value .= sprintf("\t\$mmhclass->lang['%s'] = %s%s%s;\n", $mmhclass->input->post_vars['newlang']['id'][$arraynum], $langsetdivider, ((in_array($mmhclass->input->post_vars['newlang']['type'][$arraynum], array("string", "NULL")) == false) ? $mmhclass->input->post_vars['newlang']['text'][$langindex] : nl2br(str_replace("\"", "\\\"", stripslashes($mmhclass->input->post_vars['newlang']['text'][$arraynum])))), $langsetdivider);
							}
						}
					}
					
					$example_file = implode("", file("{$mmhclass->info->root_path}source/language/default/example.php"));
					$example_file = str_replace('$LanguageIndex', $index_value, $example_file);
					$example_file = str_replace('$LanguageSettings', $text_value, $example_file);
					$example_file = str_replace('$ModificationDate', sprintf("%s - %s", time(), date("l, F d, Y, h:i:s A T O")), $example_file);
					
					if ($mmhclass->funcs->write_file("{$mmhclass->info->root_path}source/language/{$mmhclass->info->language_files[$mmhclass->input->post_vars['file_id']]}", $example_file) == false) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['011'], $mmhclass->image->basename($mmhclass->info->language_files[$mmhclass->input->post_vars['file_id']])), true);
					}
					
					if ($mmhclass->input->post_vars['set_default'] == 1) {
						if ($mmhclass->funcs->write_file("{$mmhclass->info->root_path}source/language/default/{$mmhclass->info->language_files[$mmhclass->input->post_vars['file_id']]}", $example_file) == false) {
							$mmhclass->templ->error(sprintf($mmhclass->lang['011'], $mmhclass->image->basename($mmhclass->info->language_files[$mmhclass->input->post_vars['file_id']])), true);
						}
					}
					
					$mmhclass->templ->message($mmhclass->lang['012'], true);
				}
			}
			break;
		case "language_settings-rd":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['file_id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				if ($mmhclass->funcs->is_language_file($mmhclass->input->get_vars['file_id']) == false) {
					$mmhclass->templ->error($mmhclass->lang['010'], true);	
				} else {
					if (copy("{$mmhclass->info->root_path}source/language/default/{$mmhclass->info->language_files[$mmhclass->input->get_vars['file_id']]}", "{$mmhclass->info->root_path}source/language/{$mmhclass->info->language_files[$mmhclass->input->get_vars['file_id']]}") == false) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['596'], $mmhclass->image->basename($mmhclass->info->language_files[$mmhclass->input->get_vars['file_id']])), true);
					} else {
						$mmhclass->templ->message(sprintf($mmhclass->lang['666'], $mmhclass->image->basename($mmhclass->info->language_files[$mmhclass->input->get_vars['file_id']])), true);
					}
				}
			}
			break;
		case "file_logs":      
			$filename_match = (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == false) ? $mmhclass->input->get_vars['search'] : NULL);
			
			$sortby_match = ((in_array($mmhclass->input->get_vars['sort'], array("DESC", "ASC")) == true) ? $mmhclass->input->get_vars['sort'] : "DESC");
			$orderby_match = ((in_array($mmhclass->input->get_vars['orderby'], array("log_id", "filename", "filesize", "ip_address", "time_uploaded", "gallery_id", "image_views", "bandwidth")) == true) ? $mmhclass->input->get_vars['orderby'] : "log_id");
			
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` LIKE '%[2]%' ORDER BY `[3]` [[1]] LIMIT <# QUERY_LIMIT #>;", array(MYSQL_FILE_LOGS_TABLE, $filename_match, $orderby_match), array($sortby_match));
			if ($mmhclass->db->total_rows($sql) < 1) {
				$mmhclass->templ->message((($mmhclass->funcs->is_null($filename_match) == true) ? $mmhclass->lang['003'] : $mmhclass->lang['598']), true);
			} else {
				while ($row = $mmhclass->db->fetch_array($sql)) {
					$mmhclass->templ->templ_globals['get_whileloop'] = true;
					
					$mmhclass->templ->templ_globals['file_exists'] = $mmhclass->funcs->is_file($row['filename'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']), true);
					
					$file_owners_data = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $row['gallery_id'])));
					
					$mmhclass->templ->templ_vars[] = array(
						"LOG_ID" => $row['log_id'],
					  	"FILENAME" => $row['filename'],
						"DIR_IN" => $mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']),
					  	"IP_ADDRESS" => $row['ip_address'],
					  	"IP_HOSTNAME" => gethostbyaddr($row['ip_address']),
					  	"FILESIZE" => $mmhclass->image->format_filesize($row['filesize']),
					  	"BANDWIDTH" => $mmhclass->image->format_filesize($row['bandwidth']),
					  	"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					 	"TIME_UPLOADED" => date($mmhclass->info->config['date_format'], $row['time_uploaded']),
					 	"UPLOAD_TYPE" => (($row['upload_type'] == "url") ? $mmhclass->lang['441'] : $mmhclass->lang['781']),
					  	"FILE_STATUS" => (($mmhclass->templ->templ_globals['file_exists'] == true) ? $mmhclass->lang['007'] : $mmhclass->lang['006']),
					  	"UPLOADED_BY" => (($row['gallery_id'] == 0 || $mmhclass->funcs->is_null($file_owners_data['user_id']) == true) ? $mmhclass->lang['008'] : sprintf("<a href=\"admin.php?gal=%s\">%s</a>", $file_owners_data['user_id'], $file_owners_data['username'])),
					);
					
					$mmhclass->templ->templ_globals['file_logs_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "file_logs_page");
					
					unset($file_owners_data, $mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_globals['file_exists'], $mmhclass->templ->templ_vars);       
				}
				
				$mmhclass->templ->templ_vars[] = array(
					"FILENAME_SEARCH_QUERY" => (($mmhclass->funcs->is_null($filename_match) == true) ? NULL : sprintf("&amp;search=%s", urlencode($filename_match))),
					"ORDERBY_URL_QUERY" => (($orderby_match == "log_id") ? NULL : sprintf("&amp;orderby=%s&amp;sort=", urlencode($orderby_match), urlencode($sortby_match))),
					"PAGINATION_LINKS" => $mmhclass->templ->pagelinks(sprintf("admin.php?act=file_logs%s%s%s", (($orderby_match == "log_id") ? NULL : sprintf("&amp;orderby=%s", urlencode($orderby_match))), (($sortby_match == "DESC") ? NULL : sprintf("&amp;sort=%s", urlencode($sortby_match))), (($mmhclass->funcs->is_null($filename_match) == true) ? NULL : sprintf("&amp;search=%s", urlencode($filename_match)))), $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` LIKE '%[2]%' ORDER BY `[3]` [[1]];", array(MYSQL_FILE_LOGS_TABLE, $filename_match, $orderby_match), array($sortby_match)))),
				);
				
				$mmhclass->templ->output("admin/admin", "file_logs_page");
			}
			break;
		case "file_logs-el":
			$mmhclass->db->query("TRUNCATE `[1]`;", array(MYSQL_FILE_LOGS_TABLE));
			
			$mmhclass->templ->message($mmhclass->lang['005'], true);
			break;
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
				
				exit($mmhclass->templ->parse_template("admin/admin", "delete_files_lightbox"));
			}
			break;
		case "recreate_thumbs":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['files']) == true) {
				die('You did not supply a filename!');
			} else {
				$thumbs2recreate = $mmhclass->image->basename(explode(",", $mmhclass->input->get_vars['files']));
				
				foreach ($thumbs2recreate as $filename) {
					if ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == false){
						echo 'Could not find file:<strong> '.$filename.'</strong>. Skipping.<br/>'; continue;
					}else {$mmhclass->image->create_thumbnail($filename, true, 0, $mmhclass->funcs->rand_dir($check = true, $filename));
							echo 'Recreated thumbnail for:<strong> '.$filename.' </strong><br/>';}
				}
				unset($filename);
			}	
			break;
		case "delete_files-d":
			$file_list = $mmhclass->image->basename(explode(",", (($mmhclass->funcs->is_null($mmhclass->input->get_vars['d']) == false) ? $mmhclass->input->get_vars['files'] : $mmhclass->input->post_vars['files'])));
			if ($mmhclass->funcs->is_null($file_list) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} else {
				foreach ($file_list as $id => $filename) {
					if ($mmhclass->funcs->is_null($filename) == true) {
						$mmhclass->templ->error($mmhclass->lang['530'], true);
					} elseif ($mmhclass->funcs->is_file($filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $filename), true) == false &&  $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $filename))) === 0) {
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
				
				$mmhclass->templ->templ_globals['banned_user_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "ban_control_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);       
			}
		
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '1';", array(MYSQL_BAN_FILTER_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop']['banned_ip_address_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"BAN_ID" => $row['ban_id'],
					"IP_ADDRESS" => ((($hostname = gethostbyaddr($row['ban_value'])) && $hostname !== $row['ban_value']) ? sprintf("%s (%s)", $row['ban_value'], $hostname) : $row['ban_value']),
				);
				
				$mmhclass->templ->templ_globals['banned_ip_address_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "ban_control_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $hostname);       
			}
			
			$mmhclass->templ->output("admin/admin", "ban_control_page");
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
								
										$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $row['filename']));
										$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']));
										}
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
		case "mass_email":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `username` ASC;", array(MYSQL_USER_INFO_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"USER_ID" => $row['user_id'],
					"USERNAME" => $row['username'],
					"EMAIL_ADDRESS" => $row['email_address'],
				);
				
				$mmhclass->templ->templ_globals['userlist_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "mass_email_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);    
			}
		
			$mmhclass->templ->templ_vars[] = array(
				"SPECIFIC_USER" => $mmhclass->input->get_vars['id'],
				"CAPTCHA_CODE" => recaptcha_get_html($mmhclass->info->config['recaptcha_public']),
			);
			
			$mmhclass->templ->output("admin/admin", "mass_email_page");
			break;
		case "mass_email-p":
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['post']) == true || $mmhclass->funcs->is_null($mmhclass->input->get_vars['lb_div']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));
			} else {
				$mmhclass->templ->templ_vars[] = array(
					"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					"EMAIL_MESSAGE" => wordwrap(base64_decode($mmhclass->input->get_vars['post'])),
				);
				
				exit($mmhclass->templ->parse_template("admin/admin", "mass_email_preview"));
			}
			break;
		case "mass_email-s":
			$recaptcha_check = recaptcha_check_answer($mmhclass->info->config['recaptcha_private'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->post_vars["recaptcha_challenge_field"], $mmhclass->input->post_vars["recaptcha_response_field"]);
			
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['email_subject']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['message_body']) == true) {
				$mmhclass->templ->error($mmhclass->lang['362'], true);	
			} elseif ($recaptcha_check->is_valid == false) {
				$mmhclass->templ->error($mmhclass->lang['375'], true);
			} else {
				$bcc_list = NULL;
				
				if ($mmhclass->input->post_vars['sendto_who'] == 2) {
					$user_info = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['sendto_user'])));
					
					$bcc_list = $user_info['email_address'];
				} else {
					$user_list = $mmhclass->db->query("SELECT * FROM `[1]` [[1]];", array(MYSQL_USER_INFO_TABLE), array((($mmhclass->input->post_vars['sendto_who'] == 1) ? NULL : sprintf("WHERE `user_group` = '%s'", (($mmhclass->input->post_vars['sendto_who'] == 3) ? "normal_user" : "normal_admin")))));
				
					while ($row = $mmhclass->db->fetch_array($user_list)) {
						$bcc_list .= "{$row['email_address']},";	
					}
						
					$bcc_list = substr($bcc_list, 0, -1);
				}
				
				if ($mmhclass->funcs->is_null($bcc_list) == true) {
					$mmhclass->templ->error($mmhclass->lang['371'], true);
				} else {
					$email_headers = "Bcc: {$bcc_list}\r\n";
					$email_headers .= "From: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_out']}>\r\n";
					$email_headers .= "Reply-To: {$mmhclass->info->config['site_name']} <{$mmhclass->info->config['email_in']}>\r\n";
				
					if (mail($mmhclass->info->config['email_in'], $mmhclass->input->post_vars['email_subject'], strip_tags($mmhclass->input->post_vars['message_body']), $email_headers) == true) {
						$mmhclass->templ->message($mmhclass->lang['904'], true);
					} else {
						$mmhclass->templ->error($mmhclass->lang['997']);
					}
				}
			}	
			break;
		case "user_list":
          /* User search Mod */
			$username_match = (($mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == false) ? $mmhclass->input->get_vars['search'] : NULL);
			
			$sortby_match = ((in_array($mmhclass->input->get_vars['sort'], array("DESC", "ASC")) == true) ? $mmhclass->input->get_vars['sort'] : "ASC");
			$orderby_match = ((in_array($mmhclass->input->get_vars['orderby'], array("user_id", "username", "email_address", "ip_address", "time_joined")) == true) ? $mmhclass->input->get_vars['orderby'] : "user_id");
			
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `username` LIKE '%[2]%' ORDER BY `[3]` [[1]];", array(MYSQL_USER_INFO_TABLE, $username_match, $orderby_match), array($sortby_match));
			if ($mmhclass->db->total_rows($sql) < 1) {
				$mmhclass->templ->message(sprintf((($mmhclass->funcs->is_null($username_match) == true) ? $mmhclass->lang['987'] : $mmhclass->lang['654']), $username_match), true);
			} else {
				while ($row = $mmhclass->db->fetch_array($sql)) {
					$mmhclass->templ->templ_globals['get_whileloop']['user_search_whileloop'] = true;
					
					$mmhclass->templ->templ_vars[] = array(
						"USER_ID" => $row['user_id'],
						"USERNAME" => $row['username'],
						"IP_ADDRESS" => $row['ip_address'],
						"EMAIL_ADDRESS" => $row['email_address'],
						"IP_HOSTNAME" => gethostbyaddr($row['ip_address']),
						"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					 	"TIME_JOINED" => date($mmhclass->info->config['date_format'], $row['time_joined']),
					);
					
					$mmhclass->templ->templ_globals['user_search_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "user_list_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
				}
					/* End Mod */
					
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` ORDER BY `[2]` [[1]];", array(MYSQL_USER_INFO_TABLE, $orderby_match), array($sortby_match));
		
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop']['user_list_whileloop'] = true;
				
				$mmhclass->templ->templ_vars[] = array(
					"USER_ID" => $row['user_id'],
					"USERNAME" => $row['username'],
					"IP_ADDRESS" => $row['ip_address'],
					"EMAIL_ADDRESS" => $row['email_address'],
					"IP_HOSTNAME" => gethostbyaddr($row['ip_address']),
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					"TIME_JOINED" => date($mmhclass->info->config['date_format'], $row['time_joined']),
					"GALLERY_STATUS" => (($row['private_gallery'] == 1) ? $mmhclass->lang['481'] : $mmhclass->lang['646']),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['user_id'])))),
				);
				
				$mmhclass->templ->templ_globals['user_list_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "user_list_page");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
			}
			
			$mmhclass->templ->templ_vars[] = array(
          "PAGINATION_LINKS" => $mmhclass->templ->pagelinks("admin.php?act=user_list", $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_USER_INFO_TABLE)))),
          /* User search Mod */
					"USERNAME_SEARCH_QUERY" => (($mmhclass->funcs->is_null($username_match) == true) ? NULL : sprintf("&amp;search=%s", urlencode($username_match))),
					"ORDERBY_URL_QUERY" => (($orderby_match == "user_id") ? NULL : sprintf("&amp;orderby=%s&amp;sort=", urlencode($orderby_match), urlencode($sortby_match))),
					"SEARCH_QUERY" => $username_match,
					/* End Mod */
			);
			
			$mmhclass->templ->output("admin/admin", "user_list_page");
			}
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
						"IP_ADDRESS" => $user_data['ip_address'],	   
						"EMAIL_ADDRESS" => $user_data['email_address'],
						"IP_HOSTNAME" => gethostbyaddr($user_data['ip_address']),
						"TIME_JOINED" => date($mmhclass->info->config['date_format'], $user_data['time_joined']),
						"BOXED_UPLOAD_YES" => (($user_data['upload_type'] == "boxed") ? "checked=\"checked\"" : NULL),
						"PRIVATE_GALLERY_NO" => (($user_data['private_gallery'] == 0) ? "checked=\"checked\"" : NULL),
						"PRIVATE_GALLERY_YES" => (($user_data['private_gallery'] == 1) ? "checked=\"checked\"" : NULL),
						"STANDARD_UPLOAD_YES" => (($user_data['upload_type'] == "standard") ? "checked=\"checked\"" : NULL),
						"ADMIN_USER_YES" => (($user_data['user_group'] === "normal_admin") ? "selected=\"selected\"" : NULL),
						"NORMAL_USER_YES" => (($user_data['user_group'] === "normal_user") ? "selected=\"selected\"" : NULL),
						"MODERATOR_USER_YES" => (($user_data['user_group'] === "normal_moderator") ? "selected=\"selected\"" : NULL),
						"ACCOUNT_COUNT" => $mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]` WHERE `ip_address` = '[2]';", array(MYSQL_USER_INFO_TABLE, $user_data['ip_address']))),
					);
					
					$mmhclass->templ->output("admin/admin", "user_settings_page");
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
						if ($mmhclass->input->post_vars['user_group'] == 1) {
						$mmhclass->db->query("UPDATE `[1]` SET `user_group` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, "normal_user", $user_data['user_id']));
						}
						if ($mmhclass->input->post_vars['user_group'] == 2) {
						$mmhclass->db->query("UPDATE `[1]` SET `user_group` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, "normal_moderator", $user_data['user_id']));
						}
						if ($mmhclass->input->post_vars['user_group'] == 3) {
						$mmhclass->db->query("UPDATE `[1]` SET `user_group` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, "normal_admin", $user_data['user_id']));
						}
					}
					
					if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == false && $mmhclass->input->post_vars['password'] !== "*************") {
						$mmhclass->db->query("UPDATE `[1]` SET `password` = '[2]' WHERE `user_id` = '[3]';", array(MYSQL_USER_INFO_TABLE, md5($mmhclass->input->post_vars['password']), $user_data['user_id']));
					}
					
					$mmhclass->templ->message($mmhclass->lang['330'], true);
				}
			}
			break;
		case "users-d":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->get_vars['id']));
			
			if ($mmhclass->funcs->is_null($mmhclass->input->get_vars['id']) == true) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['009']));	
			} elseif ($mmhclass->db->total_rows($sql) !== 1) {
				exit($mmhclass->templ->lightbox_error($mmhclass->lang['278']));
			} else {
				$user_data = $mmhclass->db->fetch_array($sql);
				
				if ($user_data['user_group'] === "root_admin") {
					exit($mmhclass->templ->lightbox_error($mmhclass->lang['478']));
				} else {
					$mmhclass->templ->templ_vars[] = array(
						"USER2DELETE" => $mmhclass->input->get_vars['id'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
					);
					
					exit($mmhclass->templ->parse_template("admin/admin", "delete_user_lightbox"));
				}
			}
			break;
		case "users-d-d":
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['id']));
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['id']) == true) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);	
			} elseif ($mmhclass->db->total_rows($sql) !== 1) {
				$mmhclass->templ->error($mmhclass->lang['278'], true);
			} else {
				$user_data = $mmhclass->db->fetch_array($sql);
				if ($user_data['user_group'] === "root_admin") {
					$mmhclass->templ->error($mmhclass->lang['478'], true);
				} else {
					$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `gallery_id` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $user_data['user_id']));
					while ($row = $mmhclass->db->fetch_array($sql)) {
						if ($mmhclass->funcs->is_file($row['filename'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']), true) == true) {
							unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$row['filename']);
						}
							if ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).($thumbnail = $mmhclass->image->thumbnail_name($row['filename']))) == true) {
								unlink($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $row['filename']).$thumbnail);
							}
					
							$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_RATINGS_TABLE, $row['filename']));
							$mmhclass->db->query("DELETE FROM `[1]` WHERE `filename` = '[2]';", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']));
						
					}
					$mmhclass->db->query("DELETE FROM `[1]` WHERE `user_id` = '[2]';", array(MYSQL_USER_INFO_TABLE, $user_data['user_id']));
					$mmhclass->templ->message($mmhclass->lang['157'], true);
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
				$searchUserID = htmlspecialchars($mmhclass->input->get_vars['id']);
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $searchUserID));
				
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
						
						$mmhclass->templ->templ_globals['album_options_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "move_files_lightbox");
						unset($mmhclass->templ->templ_vars, $mmhclass->templ->templ_globals['get_whileloop']);
					}
					
					$mmhclass->templ->templ_vars[] = array(
						"USER_ID" => $user_data['user_id'],
						"FILES2MOVE" => $mmhclass->input->get_vars['files'],
						"LIGHTBOX_ID" => $mmhclass->input->get_vars['lb_div'],
						"RETURN_URL" => urldecode($mmhclass->input->get_vars['return']),
					);
					
					exit($mmhclass->templ->parse_template("admin/admin", "move_files_lightbox"));
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
					
					exit($mmhclass->templ->parse_template("admin/admin", "new_album_lightbox"));
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
						
						exit($mmhclass->templ->parse_template("admin/admin", "rename_album_lightbox"));
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
						
						exit($mmhclass->templ->parse_template("admin/admin", "delete_album_lightbox"));
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
		case "images":
			$mmhclass->info->selected_album = (int)$mmhclass->input->get_vars['cat'];
			$mmhclass->info->selected_gallery = (int)$mmhclass->input->get_vars['gal'];
			
			$mmhclass->info->user_owned_gallery = (($mmhclass->funcs->is_null($mmhclass->info->selected_gallery) == false) ? true : false);
			$mmhclass->info->gallery_owner_data = (($mmhclass->info->user_owned_gallery == true) ? $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->info->selected_gallery))) : array("user_id" => 0, "username" => $mmhclass->info->config['site_name']));
			
			$mmhclass->info->gallery_url = sprintf("%sadmin.php?act=images&amp;gal=%s", $mmhclass->info->base_url, $mmhclass->info->gallery_owner_data['user_id']);
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
						
						$mmhclass->templ->templ_globals['album_pulldown_whileloop'] .= $mmhclass->templ->parse_template("admin/admin", "admin_gallery_page");
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
				
				$mmhclass->templ->output("admin/admin", "admin_gallery_page");
			}
		break;
		default:

$curVer = $mmhclass->info->version;
$getUrl = 'http://www.multihosterscript.com/version/version.txt';
$latestVer = file_get_contents($getUrl);

$curVer = explode('.', $curVer);
$latestVer = explode('.', $latestVer);

foreach($curVer as $key => $value){
if ($value < $latestVer[$key]){
$new_version = true;
$mmhclass->info->new_version = true;
}
}

$curVer = implode('.', $curVer);
$latestVer = implode('.', $latestVer);

				$mmhclass->templ->templ_vars[] = array(
					"UP_TO_DATE" => (($mmhclass->info->new_version == true) ? "Your Version: {$curVer}<br />Latest Version: {$latestVer}" : NULL),
					"TOTAL_UPLOADS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_FILE_STORAGE_TABLE)))),
					"TOTAL_MEMBERS" => $mmhclass->funcs->format_number($mmhclass->db->total_rows($mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_USER_INFO_TABLE)))),
				);
				
				$mmhclass->templ->output("admin/admin", "admin_home_page");
	}
?>
