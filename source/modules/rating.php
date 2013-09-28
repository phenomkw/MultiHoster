<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1252356976 - Monday, September 07, 2009, 04:56:16 PM EDT -0400
	// ======================================== /
	
	header("Content-Type: image/png;");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT;");
    header("Cache-Control: no-cache, must-revalidate;"); 
	header(sprintf("Content-Disposition: inline; filename=file_rating_%s.png;", mt_rand(1000, 9999)));
	
	if ($mmhclass->funcs->is_file($mmhclass->input->get_vars['file'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$mmhclass->funcs->rand_dir($check = true, $mmhclass->input->get_vars['file']), true) == false) {
		readfile("{$mmhclass->info->root_path}css/images/ratings/00000.png");
	} else {
		$filename = $mmhclass->image->basename($mmhclass->input->get_vars['file']);
		
		$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_RATINGS_TABLE, $filename));
		
		if ($mmhclass->db->total_rows($sql) == 1) {
			// 0 = empty star; 1 = half star; 2 = full star;
			
			$rating_stars = array(
				  "00000" => array(0.00, 0.00, 0.50),
				  "10000" => array(0.50, 0.50, 0.99),
				  "20000" => array(1.00, 1.50, 1.49),
				  "21000" => array(1.50, 1.50, 1.99),
				  "22000" => array(2.00, 2.00, 2.49),
				  "22100" => array(2.50, 2.50, 2.99),
				  "22200" => array(3.00, 3.00, 3.49),
				  "22210" => array(3.50, 3.50, 3.99),
				  "22220" => array(4.00, 4.00, 4.49),
				  "22221" => array(4.50, 4.50, 4.99),
				  "22222" => array(5.00, 5.00, 5.49),
			);
			
			$rating_results = $mmhclass->db->fetch_array($sql);
			
			if ($rating_results['total_votes'] >= 1) {
				$rating_total = ($rating_results['total_rating'] / $rating_results['total_votes']);
				
				foreach ($rating_stars as $star => $matches) {
					if ($rating_total >= $matches['0'] || $rating_total == $matches['1'] && $rating_total <= $matches['2']) {
						$rating_filename = $star;
					}
				}
				
				readfile("{$mmhclass->info->root_path}css/images/ratings/{$rating_filename}.png");
			} else {
				readfile("{$mmhclass->info->root_path}css/images/ratings/00000.png");
			}
		} else {
			readfile("{$mmhclass->info->root_path}css/images/ratings/00000.png");
		}
	}
	
	exit;
	
?>