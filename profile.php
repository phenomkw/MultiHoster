<?php
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
	// Public User Profiles (c) 2012 TheKPM
	// ======================================== /
	
	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/profile.php";
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);

	
	if (isset($mmhclass->input->get_vars['id'])) {
				$userid = (int)$mmhclass->input->get_vars['id'];
				$profile_data = $mmhclass->db->fetch_array($mmhclass->db->query("SELECT * FROM `[1]` WHERE `user_id` = '[2]' LIMIT 1;", array(MYSQL_USER_INFO_TABLE, $userid)));
				if($profile_data !== false) {
				 if($profile_data['email_visible'] == 1){$profile_data['email_address'] = '<a href="mailto:'.$profile_data['email_address'].'">'.$profile_data['email_address'].'</a>';
				 }else{$profile_data['email_address'] = '<i>Hidden</i>';}
				if($profile_data['country_visible'] != 1 ){$profile_data['country'] = "<i>Hidden</i>";}
				if($profile_data['city_visible'] != 1){$profile_data['city'] = "<i>Hidden</i>";}
				if($profile_data['homepage_visible'] == 1){$profile_data['homepage'] ='<a href="'.$profile_data['homepage'].'" target="_new">'.$profile_data['homepage'].'</a>';
				}else{$profile_data['homepage'] = "<i>Hidden</i>";}
				if($profile_data['facebook_visible'] == 1){$profile_data['facebook'] ='<a href="'.$profile_data['facebook'].'" target="_new">'.$profile_data['facebook'].'</a>';
				}else{$profile_data['facebook'] = "<i>Hidden</i>";}
				
				$mmhclass->templ->templ_vars[] = array(
					"USER_NAME" => $profile_data['username'],
					"TIME_JOINED" =>  date($mmhclass->info->config['date_format'], $profile_data['time_joined']),
					"FROM_COUNTRY" => $profile_data['country'],
					"FROM_CITY" => $profile_data['city'],
					"HOMEPAGE_URL" => $profile_data['homepage'],
					"FACEBOOK_PAGE" => $profile_data['facebook'],
					"EMAIL_ADDRESS" =>$profile_data['email_address'],
					"GAL_LINK" => '<a href=users.php?act=gallery&gal='.$profile_data['user_id'].'>View Gallery</a>',
	
				);
				$mmhclass->templ->output("profile", "profile_page");
				}else {$mmhclass->templ->error($mmhclass->lang['009'], true);}
	}else {

		$mmhclass->templ->error($mmhclass->lang['014'], true);
	}

?>
