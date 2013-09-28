<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1254301575 - Wednesday, September 30, 2009, 05:06:15 AM EDT -0400
	// ======================================== /
	
	/* List of language files editable by language editor. */
	
	$mmhclass->info->language_files = array(
		1 => "admin.php", 
		2 => "contact.php",
		3 => "download.php", 
		4 => "gallery.php",
		5 => "home.php", 
		6 => "info.php", 
		7 => "install.php",
		8 => "links.php", 
		9 => "tools.php", 
		10 => "upload.php",
		11 => "users.php", 
		12 => "viewer.php", 
		13 => "core/data.php",
		14 => "core/imagemagick.php", 
		15 => "core/template.php",
		16 => "modules/fileinfo.php",
		17 => "profile.php",
	);
	
	/* List of template files used by Mihalism Multi Host.
	The list is not really implemented in any features yet,
	it's just in this file so when it is, we are ready. */
	
	$mmhclass->info->template_files = array(
		1 => "contact.tpl",
		2 => "fileinfo.tpl",
		3 => "gallery.tpl",
		4 => "global.tpl",
		5 => "home.tpl",
		6 => "info.tpl",
		7 => "install.tpl",
		8 => "page_footer.tpl",
		9 => "page_header.tpl",
		10 => "tools.tpl",
		11 => "upload.tpl",
		12 => "users.tpl",
		13 => "viewer.tpl",
		14 => "admin/admin.tpl",
		15 => "admin/page_header.tpl",
		16 => "admin/page_footer.tpl",
		17 => "profile.tpl",
		18 => "modcp/modcp.tpl",
		19 => "modcp/page_header.tpl",
		20 => "modcp/page_footer.tpl",
	);
	
	/* Disabling spider logging can speed up large hosts. */
	
	define("LOG_ROBOTS", true);
	
	/* Versions. */
	
	define("PHPSAPI", PHP_SAPI);
	define("COREVERSION", "6.0.0-alpha");
	define("PHPVERSION", PHP_VERSION);
	
	/* Just to shut up a PHP error about timezone not being set.
	Mihalism Multi Host only sets the timezone below if there
	is not any already configured to be used in php.ini file. */
	
	define("DEFAULT_TIME_ZONE", "GMT");	
	
	/* Should output be compressed for faster loading?
	This setting may not be needed. Lots of servers do
	output compression automatically so, yea. */
	
	define("GZHANDLER_COMPRESSION_LEVEL", 9);	
	define("ENABLE_GZHANDLER_COMPRESSION", true);
	
	/* TidyHTML template output? Requires the 
	tidy PHP extension or will have errors. */
	
	define("ENABLE_TEMPLATE_TIDY_HTML", false);
	
	/* Check for libraries. */
	
	define("USE_GD_LIBRARY", extension_loaded("gd"));
	define("USE_GD2_LIBRARY", extension_loaded("gd2"));
	define("USE_CURL_LIBRARY", extension_loaded("curl"));
	define("USE_MYSQL_LIBRARY", extension_loaded("mysql"));
	define("USE_IMAGICK_LIBRARY", extension_loaded("imagick"));
	
	/* Check if page load is a secure load. */
	
	define("IS_HTTPS_REQUEST", isset($_SERVER['HTTPS']));
	
	/* CPU Load Monitoring. Does not work on Windows OS. */
	
	define("MAX_CPU_LOAD", 2.00);
	define("MONITOR_CPU_LOAD", false);
	
	/* How long should a request last? */
	
	define("DEFAULT_SOCKET_TIMEOUT", 2); // seconds
	
	/* Can fopen be used on remote webpages? */
	
	define("REMOTE_FOPEN_ENABLED", ini_get("allow_url_fopen")); 
	
	/* Available Functions? */
	
	define("EXIF_IS_AVAILABLE", function_exists("exif_imagetype"));
	define("FILTERS_ARE_AVAILABLE", function_exists("filter_var"));
	define("APACHE_IS_AVAILABLE", function_exists("apache_get_modules"));
	
	/* Serialized list of errors to not log. */
	
	define("ERROR_LOG_EXCEPTIONS", "a:4:{i:0;i:2;i:1;i:512;i:2;i:8;i:3;i:1024;}");
	
	/* Jailed PHP = :-( */
	
	define("PHP_IS_JAILED", (ini_get("open_basedir") || ini_get("safe_mode")));
	
	/* List of characters to be used by valid_string() and random_string(). */
	
	define("DEFAULT_RANDOM_CHARS_LIST", "abcdefghijklmnopqrstuvwxyz0123456789");
	define("DEFAULT_ALLOWED_CHARS_LIST", "-_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
				
	/* Check Operating Sytem to apply correct patches. */
	
	define("IS_DARWIN_OS", (PHP_OS == "Darwin"));
	define("IS_WINDOWS_OS", (PHP_OS == "WINNT"));
	
	/* Default MySQL Settings. */			
				
	define("MYSQL_DEFAULT_CONNECT_PORT", 3306);		
	define("MYSQL_DEFAULT_CONNECT_HOST", "localhost");	
				
	/* MySQL Table Names. It is easier to use multiple
	databases instead of editing a file of table names. */
				
	define("MYSQL_FILE_LOGS_TABLE", "mmh_file_logs");
	define("MYSQL_USER_INFO_TABLE", "mmh_user_info");
	define("MYSQL_SITE_CACHE_TABLE", "mmh_site_cache");
	define("MYSQL_ROBOT_INFO_TABLE", "mmh_robot_info");
	define("MYSQL_ROBOT_LOGS_TABLE", "mmh_robot_logs");
	define("MYSQL_BAN_FILTER_TABLE", "mmh_ban_filter");
	define("MYSQL_ADMIN_CACHE_TABLE", "mmh_admin_cache");
	define("MYSQL_FILE_RATINGS_TABLE", "mmh_file_ratings");
	define("MYSQL_FILE_STORAGE_TABLE", "mmh_file_storage");
	define("MYSQL_SITE_SETTINGS_TABLE", "mmh_site_settings");
	define("MYSQL_USER_SESSIONS_TABLE", "mmh_user_sessions");
	define("MYSQL_GALLERY_ALBUMS_TABLE", "mmh_gallery_albums");
	define("MYSQL_USER_PASSWORDS_TABLE", "mmh_user_passwords");
	define("MYSQL_ULFY_TABLE", "mmh_pict_ulfy");
	define("MYSQL_GALLERY_SESSION_TABLE", "mmh_gallery_sessions");

?>
