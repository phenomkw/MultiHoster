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
	require_once "{$mmhclass->info->root_path}source/language/install.php";

	$mmhclass->templ->page_title = $mmhclass->lang['001'];
	
	if ($mmhclass->info->site_installed == true) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);
	}
	
	switch ($mmhclass->input->get_vars['act']) {
		case "install":
			$mmhclass->templ->templ_vars[] = array(
				"MMH_VERSION" => $mmhclass->info->version,
				"SERVER_ADMIN" => $mmhclass->input->server_vars['server_admin'],
				"EMAIL_OUT" => "noreply@{$mmhclass->input->server_vars['http_host']}",
				"MYSQL_USER" => (($username = get_current_user()) ? $username : "mihalism"),
			);
			
			$mmhclass->templ->output("install", "install_form_page");
			break;
		case "precheck":
			$checks = array();
			$errorcount = 0;
			if(is_writable("{$mmhclass->info->root_path}images/") == false || is_readable("{$mmhclass->info->root_path}images/") == false) {
				$checks['folder_images'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_images'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}source/errorlog/") == false || is_readable("{$mmhclass->info->root_path}source/errorlog/") == false) {
				$checks['folder_errorlog'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_errorlog'] ='Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}source/errorlog/mysql/") == false || is_readable("{$mmhclass->info->root_path}source/errorlog/mysql/") == false) {
				$checks['folder_errorlog_mysql'] =  'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_errorlog_mysql'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}source/errorlog/php5/") == false || is_readable("{$mmhclass->info->root_path}source/errorlog/php5/") == false) {
				$checks['folder_errorlog_php5'] =  'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_errorlog_php5'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}source/tempfiles/") == false || is_readable("{$mmhclass->info->root_path}source/tempfiles/") == false) {
				$checks['folder_tempfiles'] =  'Failed <img src="css/images/xed_out.gif" alt="Failed"> - nevertheless installation and site will work';
			}else {$checks['folder_tempfiles'] ='Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}temp/") == false || is_readable("{$mmhclass->info->root_path}temp/") == false) {
				$checks['folder_imgtemp'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_imgtemp'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}temp/zip_uploads/") == false || is_readable("{$mmhclass->info->root_path}temp/zip_uploads/") == false) {
				$checks['folder_imgtemp_zip'] =  'Failed <img src="css/images/xed_out.gif" alt="Failed">'; $errorcount++;
			}else {$checks['folder_imgtemp_zip'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if(is_writable("{$mmhclass->info->root_path}source/includes/") == false) {
				$checks['folder_includes'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed"> - only needed during installation!'; $errorcount++;
			}else {$checks['folder_includes'] = 'Folder is writable. <img src="css/images/green_check.gif" alt="OK">';}
			if (!defined('PHP_VERSION_ID')) {
				$version_EMM = explode('.', PHP_VERSION);
				define('PHP_VERSION_ID', ($version_EMM[0] * 10000 + $version_EMM[1] * 100 + $version_EMM[2]));
			}
			if ( PHP_VERSION_ID < 50000) {
				$checks['php_vers'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">. Your version of PHP is too old. You are using PHP version '.PHP_VERSION.'. Upgrade to at least version 5!'; $errorcount++;
			}else { $checks['php_vers'] = 'You are running PHP version ' . PHP_VERSION . ' <img src="css/images/green_check.gif" alt="OK">'; }
			if (extension_loaded('gd') && function_exists('gd_info')) {
				$gdnfo = gd_info();
				$checks['gd_vers'] = 'GD is installed on your system.<br/>You are running version: ' . $gdnfo['GD Version'] . ' <img src="css/images/green_check.gif" alt="OK">';
			} else {$checks['gd_vers'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">. GD does not seem to be installed!'; $errorcount++;}
			if (extension_loaded('imagick')) {
				$test_imagick = new Imagick();
				$test_imagick = $test_imagick->getVersion();
				$checks['imagick_version'] = 'The Imagick Image Library is installed on your system! <br/>You are running ' . $test_imagick["versionString"] . ' <img src="css/images/green_check.gif" alt="OK">';
			} else { $checks['imagick_version'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed">. The Imagick Image Library does not seem to be installed.<br/>This is not a problem since this script can use GD, but recommended as Imagick has more features than GD';
			}if( ini_get('safe_mode') ){ 
				$checks['safe_mode'] = 'Failed <img src="css/images/xed_out.gif" alt="Failed"> - Safe Mode is turned on. Script will not work correctly! '; $errorcount++;
			}else {$checks['safe_mode'] ='Safe Mode is off. <img src="css/images/green_check.gif" alt="OK">';}
			
			$mmhclass->templ->templ_vars[] = array(
				"FOLDER_IMAGES" => $checks['folder_images'],
				"FOLDER_ERRORLOG" => $checks['folder_errorlog'],
				"FOLDER_ERRORLOG_MYSQL" => $checks['folder_errorlog_mysql'],
				"FOLDER_ERRORLOG_PHP5" => $checks['folder_errorlog_php5'],
				"FOLDER_TEMPFILES" => $checks['folder_tempfiles'],
				"FOLDER_IMGTEMP" => $checks['folder_imgtemp'],
				"FOLDER_IMGTEMP_ZIP" => $checks['folder_imgtemp_zip'],
				"FOLDER_INCLUDES" => $checks['folder_includes'],
				"PHP_VERSION_CHECK" => $checks['php_vers'],
				"PHP_GD_CHECK" => $checks['gd_vers'],
				"PHP_IMAGICK_CHECK" => $checks['imagick_version'],
				"SAFE_MODE" => $checks['safe_mode'] ,
				"NUMBER_OF_ERRORS" => $errorcount,
			);
			$mmhclass->templ->output("install", "precheck");
			break;
		case "install-d":
			if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['username']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['password']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['password-c']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['email_address']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['sql_host']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['sql_database']) == true || $mmhclass->funcs->is_null($mmhclass->input->post_vars['sql_username']) == true) {
				$mmhclass->templ->error($mmhclass->lang['003'], true);
			} elseif ($mmhclass->funcs->valid_email($mmhclass->input->post_vars['email_address']) == false) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['004'], strtolower($mmhclass->input->post_vars['email_address'])), true);
			} elseif ($mmhclass->input->post_vars['password'] !== $mmhclass->input->post_vars['password-c']) {
				$mmhclass->templ->error($mmhclass->lang['006'], true);
			} elseif (strlen($mmhclass->input->post_vars['password']) < 6 || strlen($mmhclass->input->post_vars['password']) > 30) {
				$mmhclass->templ->error($mmhclass->lang['005'], true);
			} elseif ($mmhclass->funcs->valid_string($mmhclass->input->post_vars['username']) == false || strlen($mmhclass->input->post_vars['username']) < 3 || strlen($mmhclass->input->post_vars['username']) > 30) {
				$mmhclass->templ->error(sprintf($mmhclass->lang['007'], $mmhclass->input->post_vars['username']), true);
			} elseif (is_writable("{$mmhclass->info->root_path}images/") == false || is_readable("{$mmhclass->info->root_path}images/") == false) {
				$mmhclass->templ->error($mmhclass->lang['009'], true);
			} elseif (is_writable("{$mmhclass->info->root_path}source/errorlog/") == false || is_readable("{$mmhclass->info->root_path}source/errorlog/") == false) {
				$mmhclass->templ->error($mmhclass->lang['332'], true);
			} elseif (is_writable("{$mmhclass->info->root_path}temp/") == false || is_readable("{$mmhclass->info->root_path}temp/") == false) {
				$mmhclass->templ->error($mmhclass->lang['489'], true);
			} elseif (is_writable("{$mmhclass->info->root_path}temp/zip_uploads/") == false || is_readable("{$mmhclass->info->root_path}temp/zip_uploads/") == false) {
				$mmhclass->templ->error($mmhclass->lang['490'], true);
			} else {
				if ($mmhclass->db->connect($mmhclass->input->post_vars['sql_host'], $mmhclass->input->post_vars['sql_username'], $mmhclass->input->post_vars['sql_password'], $mmhclass->input->post_vars['sql_database'], 3306, true) == false) {
					$mmhclass->templ->error($mmhclass->lang['903'], true);
				}

				$mmhclass->db->install_queries = array();
		
				$server_token = base64_encode(serialize(array("url" => $mmhclass->info->base_url, "time" => time(), "admin" => $mmhclass->input->server_vars['server_admin'], "version" => $mmhclass->info->version, "site" => $mmhclass->input->server_vars['http_host'])));
				$server_license = "";

				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_ADMIN_CACHE_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_BAN_FILTER_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_FILE_LOGS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_FILE_RATINGS_TABLE));	
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_FILE_STORAGE_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_GALLERY_ALBUMS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_SITE_CACHE_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_ROBOT_LOGS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_USER_PASSWORDS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_USER_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_USER_SESSIONS_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_ULFY_TABLE));
				$mmhclass->db->install_queries[] = array("DROP TABLE IF EXISTS `[1]`;", array(MYSQL_GALLERY_SESSION_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
					`cache_id` varchar(70) NOT NULL default '',
					`cache_value` text NOT NULL,
					PRIMARY KEY  (`cache_id`)
				) ENGINE=MyISAM;", array(MYSQL_ADMIN_CACHE_TABLE));
				
				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
					`session_key` varchar(255) NOT NULL,
					`session_time` int(10) NOT NULL,
					`image_name` varchar(255) NOT NULL,
					`tmp_name` varchar(255) NOT NULL,
					`size` int(10) NOT NULL,
					PRIMARY KEY  (`tmp_name`)
				) ENGINE=MyISAM;", array(MYSQL_ULFY_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
					`ban_id` int(25) NOT NULL auto_increment,
					`time_banned` int(10) NOT NULL default '0',
					`ban_type` tinyint(1) NOT NULL default '0',
				  	`ban_value` text NOT NULL,
				 	PRIMARY KEY  (`ban_id`)
				) ENGINE=MyISAM;", array(MYSQL_BAN_FILTER_TABLE));
				
				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`log_id` int(25) NOT NULL auto_increment,
				  	`filename` varchar(150) NOT NULL default '',
				  	`filesize` int(20) NOT NULL default '0',
				  	`ip_address` varchar(15) NOT NULL default '',
				  	`user_agent` varchar(255) NOT NULL,
				  	`time_uploaded` int(10) NOT NULL default '0',
				  	`gallery_id` int(32) NOT NULL default '0',
				  	`is_private` tinyint(1) NOT NULL default '0',
				  	`original_filename` varchar(255) NOT NULL default '',
				  	`upload_type` varchar(6) NOT NULL default 'normal',
				  	`bandwidth` int(50) NOT NULL default '0',
				  	`image_views` int(32) NOT NULL default '1',
				  	PRIMARY KEY  (`log_id`),
				  	UNIQUE KEY `filename` (`filename`)
				) ENGINE=MyISAM;", array(MYSQL_FILE_LOGS_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`rating_id` int(25) NOT NULL auto_increment,
				  	`filename` varchar(150) NOT NULL default '',
				  	`total_rating` int(5) NOT NULL default '5',
				  	`total_votes` int(30) NOT NULL default '1',
				  	`voted_by` longtext NOT NULL,
				  	`is_private` tinyint(1) NOT NULL default '0',
				  	`gallery_id` int(25) NOT NULL default '0',
				  	PRIMARY KEY  (`rating_id`),
				  	UNIQUE KEY `filename` (`filename`)
				) ENGINE=MyISAM;", array(MYSQL_FILE_RATINGS_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`file_id` int(25) NOT NULL auto_increment,
				  	`filename` varchar(150) NOT NULL default '',
				  	`is_private` tinyint(1) NOT NULL default '0',
				  	`gallery_id` int(25) NOT NULL default '0',
				  	`album_id` int(25) NOT NULL default '0',
				  	`file_title` varchar(150) NOT NULL default '',
				  	`viewer_clicks` int(25) NOT NULL default '1',
					`dir_name` varchar(15) NOT NULL default '0',
				  	PRIMARY KEY  (`file_id`),
				  	UNIQUE KEY `filename` (`filename`)
				) ENGINE=MyISAM;", array(MYSQL_FILE_STORAGE_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`album_id` int(25) NOT NULL auto_increment,
				  	`gallery_id` int(25) NOT NULL default '0',
				  	`album_title` varchar(50) NOT NULL default '',
				  	`password` varchar(32) NOT NULL default '',
				  	`is_private` tinyint(1) NOT NULL default '0',
					`has_pw` tinyint(1) NOT NULL default '0',
				  	PRIMARY KEY  (`album_id`)
				) ENGINE=MyISAM;", array(MYSQL_GALLERY_ALBUMS_TABLE));
				
				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
					`user_session` varchar(50) NOT NULL,
					`user_id` int(10) NOT NULL,
					`gallery_id` int(10) NOT NULL,
					`album_id` int(10) NOT NULL,
					`valid_till` int(11) NOT NULL,
					KEY `user_session` (`user_session`)
				) ENGINE=MyISAM;", array(MYSQL_GALLERY_SESSION_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`robot_id` int(25) NOT NULL auto_increment,
				  	`preg_match` varchar(255) NOT NULL,
				  	`robot_name` varchar(100) NOT NULL,
				  	PRIMARY KEY  (`robot_id`)
				) ENGINE=MyISAM;", array(MYSQL_ROBOT_INFO_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`log_id` int(25) NOT NULL auto_increment,
				 	`robot_id` int(25) NOT NULL default '0',
				 	`page_indexed` tinytext NOT NULL,
				  	`time_indexed` int(10) NOT NULL default '0',
				  	`ip_address` varchar(15) NOT NULL default '',
				  	`user_agent` varchar(255) NOT NULL,
				  	`http_referer` tinytext NOT NULL,
				  	PRIMARY KEY  (`log_id`)
				) ENGINE=MyISAM;", array(MYSQL_ROBOT_LOGS_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`cache_id` varchar(70) NOT NULL default '',
				 	`cache_value` text NOT NULL,
				  PRIMARY KEY  (`cache_id`)
				) ENGINE=MyISAM;", array(MYSQL_SITE_CACHE_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`config_key` varchar(70) NOT NULL default '',
				 	`config_value` text NOT NULL,
				  	PRIMARY KEY  (`config_key`),
				  	UNIQUE KEY `config_key` (`config_key`)
				) ENGINE=MyISAM;", array(MYSQL_SITE_SETTINGS_TABLE));
				
				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`password_id` int(25) NOT NULL auto_increment,
				  	`auth_key` varchar(32) NOT NULL default '',
				  	`user_id` int(25) NOT NULL default '0',
				  	`new_password` varchar(32) NOT NULL default '',
				  	`time_requested` int(10) NOT NULL default '0',
				  	`ip_address` varchar(15) NOT NULL default '0',
				  	PRIMARY KEY  (`password_id`),
				  	UNIQUE KEY `password` (`new_password`),
				  	UNIQUE KEY `auth_key` (`auth_key`)
				) ENGINE=MyISAM;", array(MYSQL_USER_PASSWORDS_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`user_id` int(25) NOT NULL auto_increment,
				  	`username` varchar(30) NOT NULL default '',
				  	`password` varchar(32) NOT NULL default '',
				  	`email_address` varchar(255) NOT NULL,
				  	`ip_address` varchar(15) NOT NULL default '',
				  	`private_gallery` tinyint(1) NOT NULL default '0',
				  	`time_joined` int(10) NOT NULL default '0',
				  	`user_group` varchar(20) NOT NULL default 'normal_user',
				  	`upload_type` varchar(8) NOT NULL default 'standard',
					`country` varchar(255) NOT NULL,
					`city` varchar(255) NOT NULL,
					`homepage` varchar(255) NOT NULL,
					`facebook` varchar(255) NOT NULL,
					`avatar` varchar(255) NOT NULL,
					`email_visible` tinyint(2) NOT NULL DEFAULT '0',
					`country_visible` tinyint(2) NOT NULL DEFAULT '0',
					`city_visible` tinyint(2) NOT NULL DEFAULT '0',
					`homepage_visible` tinyint(2) NOT NULL DEFAULT '0',
					`facebook_visible` tinyint(2) NOT NULL DEFAULT '0',
				  	PRIMARY KEY  (`user_id`),
				  	UNIQUE KEY `username` (`username`)
				) ENGINE=MyISAM;", array(MYSQL_USER_INFO_TABLE));

				$mmhclass->db->install_queries[] = array("CREATE TABLE `[1]` (
				  	`session_id` varchar(32) NOT NULL default '',
				  	`session_start` int(10) NOT NULL default '0',
				  	`user_id` int(25) NOT NULL default '0',
				  	`ip_address` varchar(15) NOT NULL default '',
				  	`user_agent` varchar(255) NOT NULL,
				  	PRIMARY KEY  (`session_id`)
				) ENGINE=MyISAM;", array(MYSQL_USER_SESSIONS_TABLE));

				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`cache_id`, `cache_value`) VALUES ('page_views', '1');", array(MYSQL_SITE_CACHE_TABLE));

				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('AdsBot-Google', 'AdsBot [Google]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('ia_archiver', 'Alexa [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Scooter/', 'Alta Vista [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Ask Jeeves', 'Ask Jeeves [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Baidurobot', 'Baidu [robot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Exabot/', 'Exabot [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('FAST Enterprise Crawler', 'FAST Enterprise [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('FAST-WebCrawler/', 'FAST WebCrawler [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('http://www.neomo.de/', 'Francis [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Gigabot/', 'Gigabot [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Mediapartners-Google/', 'Google Adsense [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Google Desktop', 'Google Desktop');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Feedfetcher-Google', 'Google Feedfetcher');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Googlebot', 'Google [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('heise-IT-Markt-Crawler', 'Heise IT-Markt [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('heritrix/1.', 'Heritrix [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('ibm.com/cs/crawler', 'IBM Research [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('ICCrawler - ICjobs', 'ICCrawler - ICjobs');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('ichiro/2', 'ichiro [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('MJ12bot/', 'Majestic-12 [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('MetagerBot/', 'Metager [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('msnbot-NewsBlogs/', 'MSN NewsBlogs');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('msnbot/', 'MSN [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('msnbot-media/', 'MSNbot Media');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('NG-Search/', 'NG-Search [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('http://lucene.apache.org/nutch/', 'Nutch [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('NutchCVS/', 'Nutch/CVS [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('OmniExplorer_Bot/', 'OmniExplorer [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('online link validator', 'Online link [Validator]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('psbot/0', 'psbot [Picsearch]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Seekbot/', 'Seekport [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Sensis Web Crawler', 'Sensis [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('SEO search Crawler/', 'SEO Crawler');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Seoma [SEO Crawler]', 'Seoma [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('SEOsearch/', 'SEOSearch [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Snappy/1.1 ( http://www.urltrends.com/ )', 'Snappy [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', 'Steeler [Crawler]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('SynooBot/', 'Synoo [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('crawleradmin.t-info@telekom.de', 'Telekom [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('TurnitinBot/', 'TurnitinBot [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('voyager/1.0', 'Voyager [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('W3 SiteSearch Crawler', 'W3 [Sitesearch]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('W3C-checklink/', 'W3C [Linkcheck]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('W3C_CSS_Validator', 'W3C [Validator]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('W3C_Validator', 'W3C [Validator]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('http://www.WISEnutbot.com', 'WiseNut [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('yacybot', 'Yacy [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Yahoo-MMCrawler/', 'Yahoo MMCrawler [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Yahoo! DE Slurp', 'Yahoo Slurp [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('Yahoo! Slurp', 'Yahoo [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`preg_match`, `robot_name`) VALUES ('YahooSeeker/', 'YahooSeeker [Bot]');", array(MYSQL_ROBOT_INFO_TABLE));

				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('max_results', '20');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('proxy_images', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('gallery_viewing', '1');", array(MYSQL_SITE_SETTINGS_TABLE));		
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('thumbnail_type', 'jpg');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('upload_path', 'images/');", array(MYSQL_SITE_SETTINGS_TABLE)); // Secret setting to set upload path
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('thumbnail_width', '160');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('useronly_uploading', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('max_filesize', '1075000');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('thumbnail_height', '160');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('uploading_disabled', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('advanced_thumbnails', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('site_name', 'MultiHoster');", array(MYSQL_SITE_SETTINGS_TABLE));		
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('registration_disabled', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('max_bandwidth', '2147483648');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('user_max_filesize', '3145728');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('date_format', 'F j, Y, g:i:s A');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('google_analytics', 'UA-1125794-2');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('user_max_bandwidth', '10737418240');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('file_extensions', 'jpeg,jpg,gif,png');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('server_license', '[2]');", array(MYSQL_SITE_SETTINGS_TABLE, $server_license));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('user_file_extensions', 'jpeg,jpg,gif,png,ico');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('recaptcha_public', '6Le1xAUAAAAAAJfAE0pXUDSvN-sHVp6y337IzLZ5');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('recaptcha_private', '6Le1xAUAAAAAAHIv7fSE0Tqn-05yf7lfWupzFrwS');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('email_in', '[2]');", array(MYSQL_SITE_SETTINGS_TABLE, $mmhclass->input->post_vars['email_address']));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('email_out', '[2]');", array(MYSQL_SITE_SETTINGS_TABLE, "noreply@{$mmhclass->input->server_vars['http_host']}"));
				
		// Begin Mod Watermark by installation
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('watermark_position', 'bottom_right');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('watermark', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
		// End Mod Watermark by installation
		
		//Begin Mod URL Shortener by installation
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('shortener', 'none');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('shortener-api-key', 'changeME');", array(MYSQL_SITE_SETTINGS_TABLE));
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('shortener-bitly-login', 'IspitONyourGRAVE');", array(MYSQL_SITE_SETTINGS_TABLE));
		//End Mod URL Shortener by installation
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('hotlink', '1');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('facebook_comments', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('max_guest_simul_upload', '2');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('max_user_simul_upload', '6');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('seo_urls', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('guest_url', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('recaptcha_guest', '1');", array(MYSQL_SITE_SETTINGS_TABLE));
		$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('show_random', '0');", array(MYSQL_SITE_SETTINGS_TABLE));
		
				$mmhclass->db->install_queries[] = array("INSERT INTO `[1]` (`username`, `password`, `email_address`, `ip_address`, `private_gallery`, `time_joined`, `user_group`, `upload_type`) VALUES ('[2]', '[3]', '[4]', '[5]', 0, '[6]', 'root_admin', 'standard');", array(MYSQL_USER_INFO_TABLE, $mmhclass->input->post_vars['username'], md5($mmhclass->input->post_vars['password']), strtolower($mmhclass->input->post_vars['email_address']), $mmhclass->input->server_vars['remote_addr'], time()));
																																												
				foreach ($mmhclass->db->install_queries as $the_query) {
					$mmhclass->db->query($the_query['0'], $the_query['1']);
				}
				
				$mmhclass->funcs->write_file("{$mmhclass->info->root_path}images/.htaccess", "\nErrorDocument 404 {$mmhclass->info->script_path}css/images/error404.gif");

				$config_string = "<?php																							  \n\n";
				$config_string .= "	\$mmhclass->info->config                 = array();   											\n";
				$config_string .= "	\$mmhclass->info->site_installed         = true; // Set to false to reinstall   			  \n\n";
				$config_string .= "	/* DATABASE INFORMATION */																	   	\n";
				$config_string .= "	\$mmhclass->info->config['sql_host']     = \"{$mmhclass->input->post_vars['sql_host']}\";		\n";
				$config_string .= "	\$mmhclass->info->config['sql_username'] = \"{$mmhclass->input->post_vars['sql_username']}\";	\n";
				$config_string .= "	\$mmhclass->info->config['sql_password'] = \"{$mmhclass->input->post_vars['sql_password']}\";	\n";
				$config_string .= "	\$mmhclass->info->config['sql_database'] = \"{$mmhclass->input->post_vars['sql_database']}\"; \n\n";
				$config_string .= "?>";
					
				if ($mmhclass->funcs->write_file("{$mmhclass->info->root_path}source/includes/config.php", $config_string) == false) {
					$mmhclass->templ->error($mmhclass->lang['010'], true);
				}
					
				chmod("{$mmhclass->info->root_path}source/includes/config.php", 0444);

				$mmhclass->templ->message($mmhclass->lang['011'], true);
			}
			break;
		default:
			$mmhclass->templ->output("install", "installer_intro_page");
	}
	
?>
