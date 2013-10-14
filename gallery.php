<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1251327136 - Wednesday, August 26, 2009, 06:52:16 PM EDT -0400
	// ======================================== /
	
	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/gallery.php";
	
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);

     if ($mmhclass->info->config['gallery_viewing'] == 0 && $mmhclass->info->is_admin == false) {
		$mmhclass->templ->error($mmhclass->lang['002'], true);
	} else {
		if ($mmhclass->input->get_vars['act'] == "show_rated") {
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `total_votes` != '0' AND `is_private` = '0' AND `gallery_id` = '0' ORDER BY `total_rating` DESC, `total_votes` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_FILE_RATINGS_TABLE));
		} else {
			$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `is_private` = '0' AND `gallery_id` = '0' ORDER BY `file_id` DESC LIMIT <# QUERY_LIMIT #>;", array(MYSQL_FILE_STORAGE_TABLE));
		}
		/*
		if ($mmhclass->db->total_rows($sql) < 1) {
			$mmhclass->templ->templ_globals['empty_gallery'] = true;
		} else {
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$imageinfo = (($mmhclass->input->get_vars['act'] == "show_rated") ? $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']))) : $row);
				
				$break_line = (($tdcount >= 4) ? true : false);
				$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
				$tdcount++;
				if ($mmhclass->info->is_admin == true){
				$mmhclass->templ->templ_globals['file_options'] = true;
				}
				$mmhclass->templ->templ_vars[] = array(
					"FILENAME" => $imageinfo['filename'],
					"FILE_TITLE" => $imageinfo['file_title'],
					"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
				);
				
				$gallery_html .= $mmhclass->templ->parse_template("global", "global_gallery_layout");
				unset($mmhclass->templ->templ_vars, $break_line, $imageinfo);	
			}
		}
		*/

		if ($mmhclass->db->total_rows($sql) < 1) {
			$mmhclass->templ->templ_globals['empty_gallery'] = true;
		} else {
			while ($row = $mmhclass->db->fetch_array($sql)) {
				$mmhclass->templ->templ_globals['get_whileloop'] = true;
				
				$imageinfo = (($mmhclass->input->get_vars['act'] == "show_rated") ? $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE, $row['filename']))) : $row);
				
				$break_line = (($tdcount >= 4) ? true : false);
				$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
				$tdcount++;
				if ($mmhclass->info->is_admin == true){
				$mmhclass->templ->templ_globals['file_options'] = true;
				}
				$mmhclass->templ->templ_vars[] = array(
					"FILENAME" => $imageinfo['filename'],
					"FILE_TITLE" => $imageinfo['file_title'],
					"TABLE_BREAK" => (($break_line == true) ? "</tr>\n<tr>" : NULL),
					"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
				);

				$mmhclass->templ->templ_globals['image_list_whileloop'] .= $mmhclass->templ->parse_template("gallery");
				unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
			}
		}

		if ($mmhclass->input->get_vars['act'] == "show_rated") {
			$pagination_count = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `total_votes` != '0' AND `is_private` = '0' AND `gallery_id` = '0' ORDER BY `total_rating` DESC, `total_votes` DESC;", array(MYSQL_FILE_RATINGS_TABLE));
		} else {
			$pagination_count = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `is_private` = '0' AND `gallery_id` = '0' ORDER BY `file_id` DESC;", array(MYSQL_FILE_STORAGE_TABLE));
		}
		if ($mmhclass->info->config['seo_urls'] == '1') {
		$mmhclass->templ->templ_vars[] = array(
			"GALLERY_HTML" => $gallery_html,
			"SITE_NAME" => $mmhclass->info->config['site_name'],
			"EMPTY_GALLERY" => $mmhclass->templ->message($mmhclass->lang['003'], false),
			"PAGINATION_LINKS" => $mmhclass->templ->pagelinks(sprintf("gallery/%s", (($mmhclass->input->get_vars['act'] == "show_rated") ? "?act=show_rated" : NULL)), $mmhclass->db->total_rows($pagination_count)),
		);
		}else {
		$mmhclass->templ->templ_vars[] = array(
			"GALLERY_HTML" => $gallery_html,
			"SITE_NAME" => $mmhclass->info->config['site_name'],
			"EMPTY_GALLERY" => $mmhclass->templ->message($mmhclass->lang['003'], false),
			"PAGINATION_LINKS" => $mmhclass->templ->pagelinks(sprintf("gallery.php%s", (($mmhclass->input->get_vars['act'] == "show_rated") ? "?act=show_rated" : NULL)), $mmhclass->db->total_rows($pagination_count)),
		);
		}	
		$mmhclass->templ->output("gallery");
	}

?>
