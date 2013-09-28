<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1252976529 - Monday, September 14, 2009, 09:02:09 PM EDT -0400
	// ======================================== /
	
	class mmhclass_mysql_driver
	{
		// Class Initialization Method
		function __construct() { global $mmhclass; $this->mmhclass = &$mmhclass; }
		
		function connect($host = MYSQL_DEFAULT_CONNECT_HOST, $username, $password, $database, $port = MYSQL_DEFAULT_CONNECT_PORT, $boolerror = false)
		{
			if (USE_MYSQL_LIBRARY == false) {
				$this->mmhclass->templ->fatal_error("Sorry, but Mihalism Multi Host will not work without MySQL loaded as a PHP extension.");
			} else {
				$connection_id = mysql_connect("{$host}:{$port}", $username, $password, false);
				
				if (is_resource($connection_id) == false) {
					return $this->error(NULL, NULL, $boolerror);
				} else {
					if (mysql_select_db($database, $connection_id) == false) {
						return $this->error(NULL, NULL, $boolerror);
					} else {
						if (is_resource($this->root_connection) == false) {
							$this->root_connection = $connection_id;
						}
					}
				}
				
				return $connection_id;
			}
		}
		
		function close()
		{
			if (is_resource($this->root_connection) == true) {
				mysql_close($this->root_connection);
			}
		}
		
		function query($query, $input = NULL, $addon = NULL)
		{
			if (is_resource($this->root_connection) == false) {
				$this->connect($this->mmhclass->info->config['sql_host'], $this->mmhclass->info->config['sql_username'], $this->mmhclass->info->config['sql_password'], $this->mmhclass->info->config['sql_database']);
			}
			
			if (strpos($query, "<# QUERY_LIMIT #>") == true) {				
				$query = str_replace("<# QUERY_LIMIT #>", sprintf("%s, {$this->mmhclass->info->config['max_results']}", (($this->mmhclass->info->current_page * $this->mmhclass->info->config['max_results']) - $this->mmhclass->info->config['max_results'])), $query);
			}
			
			if (is_array($addon) == true && empty($addon) == false) {
				foreach ($addon as $key => $replacement) {
					$query = str_replace(sprintf("[[%s]]", ($key + 1)), stripslashes($replacement), $query);
				}
			}
			
			if (is_array($input) == true && empty($input) == false) {
				foreach ($input as $key => $replacement) {
					$query = str_replace(sprintf("[%s]", ($key + 1)), mysql_real_escape_string(str_replace(array("[", "]"), array("\[", "\]"), stripslashes($replacement))), $query);
				}
			}
			
			$query = str_replace(array("\[", "\]"), array("[", "]"), $query);
			$this->query_result = mysql_query($query, $this->root_connection);
			
			return (($this->query_result == false) ? $this->error($query) : $this->query_result);
		}
		
		function total_rows($query_id)
		{
			return mysql_num_rows($query_id);
		}
		
		function fetch_array($query_id, $result_type = MYSQL_ASSOC)
		{
			return mysql_fetch_array($query_id, $result_type);
		}
		
		function error($query = "No Query Executed", $custom_error = NULL, $returnerr = false)
		{
			$error_message = "====================================================================\n";
			$error_message .= sprintf("Query Executed: %s\n", $query);
			$error_message .= sprintf("Time Encountered: %s\n", date("F j, Y, g:i:s A"));
			$error_message .= sprintf("URL Location: %s\n", $this->mmhclass->info->page_url);
			$error_message .= sprintf("IP Address: %s\n", $this->mmhclass->input->server_vars['remote_addr']);
			$error_message .= sprintf("Error: %s\n", (($this->mmhclass->funcs->is_null($custom_error) == false) ? $custom_error : mysql_error()));
			$error_message .= sprintf("Error Number: %s\n", (($this->mmhclass->funcs->is_null(mysql_error()) == false) ? mysql_errno() : "Unknown Error Number"));
			$error_message .= "====================================================================\n";
			
			@file_put_contents(sprintf("%ssource/errorlog/mysql/%s.log", ROOT_PATH, date("m-d-Y")), $error_message, FILE_APPEND);
			
			if ($returnerr == true) {
				return false;
			} else {
				output_fatal_error("MySQL Driver Error"); 
			} 
			
			return true;
		}
	}
	
?>