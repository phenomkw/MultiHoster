<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1254301731 - Wednesday, September 30, 2009, 05:08:51 AM EDT -0400
	// ======================================== /
	
	//
	// clearstatcache(); 
	//
	// The clearstatcache() function was introduced when Mihalism Multi Host was not 
	// very old and not advanced. Now that it is powerful enough, it no longer needs to
	// have this function part of its overall design. It is now considered deprecated.
	//

	$mmhclass = new stdClass;
	$mmhclass->info = new stdClass;
	$mmhclass->input = new stdClass;

	ini_set("log_errors", 1);
	ini_set("display_errors", 0);
	ini_set("memory_limit", "128M");
	ini_set("post_max_size", "128M");
	
	
	define("ROOT_PATH", sprintf("%s/", realpath(".")));
	$mmhclass->info->root_path = ROOT_PATH; // Backwards compatibility

	require_once "{$mmhclass->info->root_path}source/includes/catcherror.php";
	
	set_error_handler("error_handler", E_ALL);
	register_shutdown_function("shutdown_error_handler"); 
	
	if (is_file("{$mmhclass->info->root_path}source/includes/config.php") == true) {
		require_once "{$mmhclass->info->root_path}source/includes/config.php";
	}
	
	require_once "{$mmhclass->info->root_path}source/includes/database.php";
	require_once "{$mmhclass->info->root_path}source/includes/template.php";
	require_once "{$mmhclass->info->root_path}source/includes/functions.php";
	require_once "{$mmhclass->info->root_path}source/includes/imagemagick.php";
	require_once "{$mmhclass->info->root_path}source/includes/recaptchalib.php";

	$mmhclass->funcs = new mmhclass_core_functions();
	$mmhclass->templ = new mmhclass_template_engine();
	$mmhclass->image = new mmhclass_image_functions();
	$mmhclass->db = new mmhclass_mysql_driver();
	 
	require_once "{$mmhclass->info->root_path}source/includes/initindex.php";
	require_once "{$mmhclass->info->root_path}source/language/core/data.php";
	require_once "{$mmhclass->info->root_path}source/language/core/template.php";
	require_once "{$mmhclass->info->root_path}source/language/core/imagemagick.php";
	
	$mmhclass->input->get_vars = $mmhclass->funcs->clean_array($_GET);  
	$mmhclass->input->post_vars = $mmhclass->funcs->clean_array($_POST);
	$mmhclass->input->server_vars = $mmhclass->funcs->clean_array($_SERVER);
	$mmhclass->input->cookie_vars = $mmhclass->funcs->clean_array($_COOKIE);
	$mmhclass->input->session_vars = $mmhclass->funcs->clean_array($_SESSION);
	$mmhclass->input->file_vars = $_FILES; // $_FILES no longer is cleaned so backslashes not removed

	$mmhclass->info->version = COREVERSION;
	$mmhclass->info->init_time = $mmhclass->funcs->microtime_float();
	$mmhclass->info->page_url = $mmhclass->funcs->fetch_url(true, false, true);
	$mmhclass->info->base_url = $mmhclass->funcs->fetch_url(false, false, false);
	$mmhclass->info->current_page = round(($mmhclass->input->get_vars['page'] >= 1) ? $mmhclass->input->get_vars['page'] : 1);
	$mmhclass->info->script_path = ((($path = dirname($mmhclass->input->server_vars['php_self'])) == "/") ? $path : "{$path}/");
			
	$mmhclass->image->manipulator = ((USE_IMAGICK_LIBRARY == true) ? "imagick" : ((USE_GD_LIBRARY == true || USE_GD2_LIBRARY == true) ? "gd" : $mmhclass->templ->fatal_error($mmhclass->lang['7414'])));
	 
	if (version_compare(PHPVERSION, "5.0.0", "<") == true) { 
		$mmhclass->templ->fatal_error(sprintf($mmhclass->lang['9553'], $mmhclass->info->version));
	}	
	
	if (version_compare(PHPVERSION, "5.1.0", ">=") == true) { 
		if (ini_get("date.timezone") == false) {
			date_default_timezone_set(DEFAULT_TIME_ZONE);
		}
	}
	
	if (MONITOR_CPU_LOAD == true && IS_WINDOWS_OS == false) {
		$load_average = sys_getloadavg();
		
		if ($load_average['0'] > MAX_CPU_LOAD) {
			// Header and exit taken right from the PHP Manual
			
			header("HTTP/1.1 503 Too busy, try again later");
   			output_fatal_error("Server too busy. Please try again later.");
		}
	}
	
	if (ENABLE_GZHANDLER_COMPRESSION == true) {
		if (ini_get("zlib.output_compression") == false) {
			ob_start(array("ob_gzhandler", GZHANDLER_COMPRESSION_LEVEL));
		}
	}
	
	if ($mmhclass->info->site_installed == false) {
		if ($mmhclass->image->basename($mmhclass->input->server_vars['php_self']) !== "install.php") {
			$mmhclass->templ->page_title = $mmhclass->lang['6897'];
			$mmhclass->templ->message($mmhclass->lang['5435'], true);
		}
	} else {
		if (isset($mmhclass->input->get_vars['rurl']) == true) {
			header(sprintf("Location: %s", base64_decode($mmhclass->input->get_vars['rurl']))); exit;
		}
		
		$mmhclass->db->query("UPDATE `[1]` SET `cache_value` = `cache_value` + 1 WHERE `cache_id` = 'page_views';", array(MYSQL_SITE_CACHE_TABLE)); 

		$sql = $mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_SITE_CACHE_TABLE));
		while ($row = $mmhclass->db->fetch_array($sql)) {
			$mmhclass->info->site_cache[$row['cache_id']] = $row['cache_value'];
		}

		$sql = $mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_SITE_SETTINGS_TABLE));
		while ($row = $mmhclass->db->fetch_array($sql)) {
			$mmhclass->info->config[$row['config_key']] = $row['config_value'];
		}
		
		// Robot logging can slow large hosts down.
		
		if (LOG_ROBOTS == true) {
			$sql = $mmhclass->db->query("SELECT * FROM `[1]`;", array(MYSQL_ROBOT_INFO_TABLE));
			
			while ($row = $mmhclass->db->fetch_array($sql)) {
				if (stripos(html_entity_decode($mmhclass->input->server_vars['http_user_agent']), $row['preg_match']) !== false) {
					$mmhclass->db->query("INSERT INTO `[7]` (`robot_id`, `page_indexed`, `time_indexed`, `ip_address`, `user_agent`, `http_referer`) VALUES ('[1]', '[2]', '[3]', '[4]', '[5]', '[6]');", array($row['robot_id'], str_replace($mmhclass->info->base_url, NULL, $mmhclass->info->page_url), time(), $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->server_vars['http_user_agent'], $mmhclass->input->server_vars['http_referer'], MYSQL_ROBOT_LOGS_TABLE));
					$mmhclass->info->is_robot = true; 
				}
			}
		}

		if (isset($mmhclass->input->cookie_vars['mmh_user_session']) == true && $mmhclass->info->is_robot == false) {
			$mmhclass->info->user_session = unserialize(stripslashes(base64_decode($mmhclass->input->cookie_vars['mmh_user_session'])));
			
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' AND `session_id` = '[3]' AND `ip_address` = '[4]' LIMIT 1;", array(MYSQL_USER_SESSIONS_TABLE, $mmhclass->info->user_session['user_id'], $mmhclass->info->user_session['session_id'], $mmhclass->input->server_vars['remote_addr']));
			
			if ($mmhclass->db->total_rows($sql) === 1) {
				$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' AND `ip_address` = '[3]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $mmhclass->info->user_session['user_id'], $mmhclass->input->server_vars['remote_addr']));
				
				if ($mmhclass->db->total_rows($sql) === 1) {
					$mmhclass->info->user_data = $mmhclass->db->fetch_array($sql); 
					
					$mmhclass->info->is_user = (($mmhclass->funcs->is_null($mmhclass->info->user_data['username']) == false) ? true : false); 
					$mmhclass->info->is_root = (($mmhclass->info->user_data['user_group'] === "root_admin" && $mmhclass->info->is_user == true) ? true : false);
					$mmhclass->info->is_admin = (($mmhclass->info->is_root == true || $mmhclass->info->user_data['user_group'] === "normal_admin" && $mmhclass->info->is_user == true) ? true : false);
					$mmhclass->info->is_mod = (($mmhclass->info->user_data['user_group'] === "normal_moderator" && $mmhclass->info->is_user == true) ? true : false);
				}
			}
		}
		
		if ($mmhclass->info->is_user == true) {
			$mmhclass->info->config['max_filesize'] = $mmhclass->info->config['user_max_filesize'];
			$mmhclass->info->config['max_bandwidth'] = $mmhclass->info->config['user_max_bandwidth'];
			$mmhclass->info->config['file_extensions'] = $mmhclass->info->config['user_file_extensions'];
			
			unset($mmhclass->info->config['user_file_extensions'], $mmhclass->info->config['user_max_filesize'], $mmhclass->info->config['user_max_bandwidth']);
		}

		$mmhclass->info->config['file_extensions'] = explode(",", $mmhclass->info->config['file_extensions']);

		if ($mmhclass->info->is_root == false) {
			
			//
			// Using preformed matches seems easier than regular expression
			// although the site owner will still think that it is regex. 
			// 
			// Match types:
			//		1. 123.123.*.*
			//		2. 123.123.*.123
			//		3. 123.123.123.*
			//		
			
			$ip_parts = explode(".", $mmhclass->input->server_vars['remote_addr'], 4);

			$sql = $mmhclass->db->query("SELECT `ban_value` FROM `[1]` WHERE `ban_type` = '1' AND (`ban_value` = '[2]' OR `ban_value` = '[3]' OR `ban_value` = '[4]' OR `ban_value` = '[5]') LIMIT 1;", array(MYSQL_BAN_FILTER_TABLE, $mmhclass->input->server_vars['remote_addr'], "{$ip_parts['0']}.{$ip_parts['1']}.*.*", "{$ip_parts['0']}.{$ip_parts['1']}.*.{$ip_parts['3']}", "{$ip_parts['0']}.{$ip_parts['1']}.{$ip_parts['2']}.*"));
			
			if ($mmhclass->db->total_rows($sql) == 1) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['4648'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->info->config['site_name']), true); 	
			} else {
				if ($mmhclass->info->is_user == true) {
					$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `ban_type` = '2' AND `ban_value` = '[2]' LIMIT 1;", array(MYSQL_BAN_FILTER_TABLE, $mmhclass->info->user_data['username']));
					
					if ($mmhclass->db->total_rows($sql) == 1) {
						$mmhclass->templ->error(sprintf($mmhclass->lang['1188'], $mmhclass->info->user_data['username'], $mmhclass->info->config['site_name']), true);	
					}
				}
			}
		}
		
		if (isset($mmhclass->input->get_vars['version']) == true) {
			header("Content-Type: text/plain;");
			header("Content-Disposition: inline; filename=mmhcheck.txt;");
			
			exit(sprintf($mmhclass->lang['2761'], $mmhclass->info->base_url, $mmhclass->info->version));
		}
	}

?>