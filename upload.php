<?php 
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
	// ======================================== 
	// Contains parts of upload.php from
	//
	// Copyright 2009, Moxiecode Systems AB
	// Released under GPL License.
	//
	// License: http://www.plupload.com/license
	// Contributing: http://www.plupload.com/contributing
	// ======================================== /
	
	require_once "./source/includes/data.php";
	require_once "{$mmhclass->info->root_path}source/language/upload.php";
	$mmhclass->funcs->theCleaner();
	$mmhclass->templ->page_title = sprintf($mmhclass->lang['001'], $mmhclass->info->config['site_name']);
	
	
	if (isset($mmhclass->input->post_vars['session']) && $mmhclass->input->post_vars['upload_it'] == true) {
        $u_check = substr($mmhclass->input->post_vars['session'], strlen($mmhclass->input->post_vars['session'] )-1);
	
	if ($mmhclass->info->config['uploading_disabled'] == true && $u_check != 9) {
		$mmhclass->templ->page_title = $mmhclass->lang['005'];
		$mmhclass->templ->error($mmhclass->lang['004'], true);
	} else {
		if ($mmhclass->info->config['useronly_uploading'] == true) {
			if  ($u_check != 9 && $u_check != 1) {
			$mmhclass->templ->page_title = $mmhclass->lang['005'];
			$mmhclass->templ->error($mmhclass->lang['007'], true);
			}
		}
	}
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$targetDir = "{$mmhclass->info->root_path}temp";
//	$targetDir = 'temp';
	$cleanupTargetDir = true;
	$maxFileAge = 2 * 3600; 
	@set_time_limit(5 * 60);
	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
	$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);
		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;
		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}
	$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
	if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
				@unlink($tmpfilePath);
			}
		}
		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				$in = fopen($_FILES['file']['tmp_name'], "rb");
				if ($in) {
					while ($buff = fread($in, 4096))
					fwrite($out, $buff);
				} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else	
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		$in = fopen("php://input", "rb");
		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
			} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		fclose($in);
		fclose($out);
	} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}
	if (!$chunks || $chunk == $chunks - 1) {
		rename("{$filePath}.part", $filePath);
	}
	$origname = $mmhclass->image->basename($filePath);
	$filesize = filesize($filePath);
	$filetitle = strip_tags((strlen($origname) > 56) ? sprintf("%s...", substr($origname, 0, 53)) : $origname);
	$filename = sprintf("%s.%s", md5(microtime()).mt_rand(), ($extension = $mmhclass->image->file_extension($origname)));
	if (in_array($extension, $mmhclass->info->config['file_extensions']) == false) {
		unlink($filePath);
		die('{"jsonrpc" : "2.0", "error" : {"code": 110, "message": "No valid file extension."}}');
	} elseif ($mmhclass->image->is_image($filePath) == false) {
		unlink($filePath);
		die('{"jsonrpc" : "2.0", "error" : {"code": 111, "message": "No valid image!."}, "id" : "id"}');
	} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.'temp/'.$filename) == true) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 112, "message": "Target file already exists."}, "id" : "id"}');
	} elseif (rename($filePath, $mmhclass->info->root_path.'temp/'.$filename) == false) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 113, "message": "Temp file could not be moved to target."}, "id" : "id"}');
	} else {
		$mmhclass->db->query("INSERT INTO `[1]` (`session_key`, `session_time`,`image_name`,`tmp_name`,`size`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]');", array(MYSQL_ULFY_TABLE,$mmhclass->input->post_vars['session'], time(), $origname, $filename, $filesize));
		unset($origname, $filetitle, $filename, $extension);
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
}
	
	
	
	if ($mmhclass->info->config['uploading_disabled'] == true && $mmhclass->info->is_admin == false) {
		$mmhclass->templ->page_title = $mmhclass->lang['005'];
		$mmhclass->templ->error($mmhclass->lang['004'], true);
	} else {
		if ($mmhclass->info->config['useronly_uploading'] == true && $mmhclass->info->is_user == false) {
			$mmhclass->templ->page_title = $mmhclass->lang['005'];
			$mmhclass->templ->error($mmhclass->lang['007'], true);
		}
	}
	
	switch ($mmhclass->input->post_vars['upload_type']) {
		case "url-boxed":
		case "url-standard":
			if (REMOTE_FOPEN_ENABLED == false && USE_CURL_LIBRARY == false) {
				$mmhclass->templ->error($mmhclass->lang['011'], true);
			} else {
				$files = $mmhclass->input->post_vars['userfile'];
				$mmhclass->input->post_vars['userfile'] = array();
				
				switch ($mmhclass->input->post_vars['url_upload_type']) {
					case "paste_upload":

                        $mmhclass->input->post_vars['userfile'] = array_map("trim", explode("\n", $mmhclass->input->post_vars['paste_upload'], $mmhclass->info->config['max_results']));
						break;
					case "webpage_upload":

                        if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['webpage_upload']) == false) {
							$urlparts = parse_url($mmhclass->input->post_vars['webpage_upload']);
							
							$webpage_headers = $mmhclass->funcs->get_headers($mmhclass->input->post_vars['webpage_upload']);
							$webpage_content = $mmhclass->funcs->get_http_content($webpage_headers['Address'], 2);
							
							if ($mmhclass->funcs->is_null($webpage_content) == true) {
								$mmhclass->templ->error($mmhclass->lang['743'], true);
							} else {
								$file_extensions = implode("|", $mmhclass->info->config['file_extensions']);
								$webpage_content = preg_replace('/\s/', '%20', $webpage_content);
								preg_match_all(sprintf("#<img([^\>]+)src=('|\"|)([^\s]+)\.((%s)[^\?]+)('|\"|)#Ui", $file_extensions), $webpage_content, $image_matches);
								
								$image_matches['3'] = array_unique($image_matches['3']);
								
								foreach ($image_matches['3'] as $id => $url) {
									if ($id < $mmhclass->info->config['max_results']) {
										if (preg_match("#^(http|https):\/\/([^\s]+)$#i", $url) >= 1) {
											$mmhclass->input->post_vars['userfile'][] = sprintf("%s.%s", $url, $image_matches['5'][$id]);
										} elseif (preg_match("#^\/([^\s]+)$#", $url) >= 1) {
											$mmhclass->input->post_vars['userfile'][] = sprintf("%s://%s%s.%s", $urlparts['scheme'], $urlparts['host'], $url, $image_matches['5'][$id]);
										} else {
											$mmhclass->input->post_vars['userfile'][] = sprintf("%s://%s%s%s.%s", $urlparts['scheme'], $urlparts['host'], sprintf("%s/", dirname($urlparts['path'])), $url, $image_matches['5'][$id]);
										}
									}
								}
							}
							
							if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['userfile']) == true) {
								$mmhclass->templ->error($mmhclass->lang['254'], true);
							} else {
								foreach ($mmhclass->input->post_vars['userfile'] as $imageurl) {
									$mmhclass->templ->templ_globals['get_whileloop'] = true;
									
									$break_line = (($tdcount >= 4) ? true : false);
									$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
									$tdcount++;
									
									$mmhclass->templ->templ_vars[] = array(
										"IMAGE_URL" => $imageurl,
										"FILENAME" => $mmhclass->image->basename($imageurl),
										"MAX_WIDTH" => $mmhclass->info->config['thumbnail_width'],
										"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
										"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
									);
									
									$mmhclass->templ->templ_globals['urlupload_gallery_layout'] .= $mmhclass->templ->parse_template("upload", "webpage_upload_image_select");
									unset($mmhclass->templ->templ_vars, $break_line, $mmhclass->templ->templ_globals['get_whileloop']);	
								}
							
								$mmhclass->templ->templ_vars[] = array(
									"WEBPAGE_URL" => $webpage_headers['Address'],
									"UPLOAD_TO" => $mmhclass->input->post_vars['upload_to'],
									"UPLOAD_TYPE" => $mmhclass->input->post_vars['upload_type'],
									"IMAGE_RESIZE" => $mmhclass->input->post_vars['image_resize'],
									"PRIVATE_UPLOAD" => $mmhclass->input->post_vars['private_upload'],
									"WEBPAGE_URL_SMALL" => $mmhclass->funcs->shorten_url($webpage_headers['Address'], 60),
								);
							
								$mmhclass->templ->output("upload", "webpage_upload_image_select");	
							}
						}
						break;
					default:
						$mmhclass->input->post_vars['userfile'] = $files;
				}
				
				$total_files = count($mmhclass->input->post_vars['userfile']);
				
				foreach ($mmhclass->input->post_vars['userfile'] as $i => $name) {
					if ($mmhclass->funcs->is_null($mmhclass->input->post_vars['userfile'][$i]) == false && $mmhclass->input->post_vars['userfile'][$i] !== "http://") {
						if ($total_file_uploads < $total_files) {
							$origname = preg_replace('/[^\w\._]+/', '_', $mmhclass->image->basename($mmhclass->input->post_vars['userfile'][$i]));
							$rnd_dir_name = $mmhclass->funcs->rand_dir();
							$filetitle = strip_tags((strlen($origname) > 40) ? sprintf("%s...", substr($origname, 0, 40)) : $origname);
							$shortened_name = strip_tags((strlen($origname) > 55) ? sprintf("%s", substr($origname, 0, 55)) : $origname);
							$filename = sprintf("%s_%s_%s.%s", $mmhclass->funcs->random_string(4, "0123456789"),  $mmhclass->image->basename($shortened_name, '.'.$mmhclass->image->file_extension($origname)), $mmhclass->funcs->random_string(4),($extension = $mmhclass->image->file_extension($origname)));
							$filename = preg_replace('/(\s+|\%)/', '', $filename);
							//$filename = sprintf("%s.%s", $mmhclass->funcs->random_string(20, "0123456789"), ($extension = $mmhclass->image->file_extension($origname)));
							
							$file_headers = $mmhclass->funcs->get_headers(preg_replace('/\s/', '%20', $mmhclass->input->post_vars['userfile'][$i]));
							$file_content = ((in_array("HTTP/1.0 200 OK", $file_headers) == true || in_array("HTTP/1.1 200 OK", $file_headers) == true) ? $mmhclass->funcs->get_http_content($file_headers['Address'], 2) : NULL);
							
							if ($mmhclass->funcs->is_url($file_headers['Address']) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['012'], $origname), "error");
							} elseif ($mmhclass->funcs->is_null($file_content) == true) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['013'], $origname), "error");
							} elseif (in_array($extension, $mmhclass->info->config['file_extensions']) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['002'], $origname, $extension), "message");
							} elseif (($filesize = strlen($file_content)) > $mmhclass->info->config['max_filesize']) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['003'], $origname, $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize'])), "message");
							} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == true) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['009'], $origname), "error");
							} elseif ($mmhclass->funcs->write_file($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $file_content) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['010'], $origname), "error");
							} else {
								if ($mmhclass->input->post_vars['image_resize'] > 0 && $mmhclass->input->post_vars['image_resize'] <= 8) {
									rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'rz_'.$filename);
									$mmhclass->image->create_thumbnail($filename, true, $mmhclass->input->post_vars['image_resize'], $rnd_dir_name);
								}
			 // BEGIN WATERMARK MOD
							if($mmhclass->info->config['watermark'] == '1') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
								rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'wm_'.$filename);
								$mmhclass->image->watermark($filename, $rnd_dir_name);
							}
							if($mmhclass->info->config['watermark'] == '0') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
							}
			// END WATERMARK MOD
								
								chmod($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, 0644);
								
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `total_rating`, `total_votes`, `voted_by`, `gallery_id`, `is_private`) VALUES ('[2]', '0', '0', '', '[3]', '[4]');", array(MYSQL_FILE_RATINGS_TABLE, $filename, $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload']));
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `is_private`, `gallery_id`, `file_title`, `album_id`,`dir_name`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]'); ", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->input->post_vars['private_upload'], $mmhclass->info->user_data['user_id'], $filetitle, $mmhclass->input->post_vars['upload_to'], $rnd_dir_name));																																							
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `filesize`, `ip_address`, `user_agent`, `time_uploaded`, `gallery_id`, `is_private`, `original_filename`, `upload_type`, `bandwidth`, `image_views`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]', '[8]', '[9]', 'url', '[3]', '1'); ", array(MYSQL_FILE_LOGS_TABLE, $filename, $filesize, $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->server_vars['http_user_agent'], time(), $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload'], strip_tags($origname)));
								
								
								
								$uploadinfo[]['result'] = $filename;							
							
								unset($origname, $filetitle, $filename, $file_headers, $file_content, $filesize, $extension);
							}
							
							$total_file_uploads++;
						}
					}
				}
			}
			break;
		case "standard":
		case "normal-boxed":
			$total_files = count($mmhclass->input->file_vars['userfile']['name']);
			
			foreach ($mmhclass->input->file_vars['userfile']['name'] as $i => $name) {
				if (array_key_exists($i, $mmhclass->input->file_vars['userfile']['error']) == false && array_key_exists($i, $mmhclass->input->file_vars['userfile']['name']) == true || array_key_exists($i, $mmhclass->input->file_vars['userfile']['error']) == true && array_key_exists($i, $mmhclass->input->file_vars['userfile']['name']) == true) {
					if (array_key_exists($i, $mmhclass->input->file_vars['userfile']['error']) == false && $mmhclass->funcs->is_null($mmhclass->input->file_vars['userfile']['name'][$i]) == false || $mmhclass->input->file_vars['userfile']['error'][$i] !== 4 && $mmhclass->funcs->is_null($mmhclass->input->file_vars['userfile']['name'][$i]) == false) {
						if ($total_file_uploads < $total_files) {
							$origname = preg_replace('/[^\w\._]+/', '', $mmhclass->image->basename($mmhclass->input->file_vars['userfile']['name'][$i]));
							$rnd_dir_name = $mmhclass->funcs->rand_dir();
							$filetitle = strip_tags((strlen($origname) > 40) ? sprintf("%s...", substr($origname, 0, 40)) : $origname);
							$shortened_name = strip_tags((strlen($origname) > 55) ? sprintf("%s", substr($origname, 0, 55)) : $origname);
							$filename = sprintf("%s_%s_%s.%s", $mmhclass->funcs->random_string(4, "0123456789"),  $mmhclass->image->basename($shortened_name, '.'.$mmhclass->image->file_extension($origname)), $mmhclass->funcs->random_string(4),($extension = $mmhclass->image->file_extension($origname)));
							$filename = preg_replace('/(\s+|\%)/', '', $filename);
													
							if (in_array($extension, $mmhclass->info->config['file_extensions']) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['002'], $origname, $extension), "message");
							} elseif ($mmhclass->input->file_vars['userfile']['size'][$i] > $mmhclass->info->config['max_filesize']) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['003'], $origname, $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize'])), "message");
							} elseif ($mmhclass->image->is_image($mmhclass->input->file_vars['userfile']['tmp_name'][$i]) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['006'], $origname), "message");
							} elseif ($mmhclass->input->file_vars['userfile']['error'][$i] > 0) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['008'][$mmhclass->input->file_vars['userfile']['error'][$i]], $origname), "error");
							} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == true) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['009'], $filename), "error");
							} elseif (move_uploaded_file($mmhclass->input->file_vars['userfile']['tmp_name'][$i], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == false) {
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['010'], $origname), "error");
							} else {
								if ($mmhclass->input->post_vars['image_resize'] > 0 && $mmhclass->input->post_vars['image_resize'] <= 8) {
									rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'rz_'.$filename);
									$mmhclass->image->create_thumbnail($filename, true, $mmhclass->input->post_vars['image_resize'], $rnd_dir_name);
								}
			// BEGIN WATERMARK MOD
							if($mmhclass->info->config['watermark'] == '1') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
								rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'wm_'.$filename);
								$mmhclass->image->watermark($filename, $rnd_dir_name);
							}
							if($mmhclass->info->config['watermark'] == '0') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
							}
			// END WATERMARK MOD								
								chmod($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, 0644);
								
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `total_rating`, `total_votes`, `voted_by`, `gallery_id`, `is_private`) VALUES ('[2]', '0', '0', '', '[3]', '[4]');", array(MYSQL_FILE_RATINGS_TABLE, $filename, $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload']));
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `is_private`, `gallery_id`, `file_title`, `album_id`, `dir_name`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]'); ", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->input->post_vars['private_upload'], $mmhclass->info->user_data['user_id'], $filetitle, $mmhclass->input->post_vars['upload_to'], $rnd_dir_name));																																							
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `filesize`, `ip_address`, `user_agent`, `time_uploaded`, `gallery_id`, `is_private`, `original_filename`, `upload_type`, `bandwidth`, `image_views`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]', '[8]', '[9]', 'normal', '[3]', '1'); ", array(MYSQL_FILE_LOGS_TABLE, $filename, $mmhclass->input->file_vars['userfile']['size'][$i], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->server_vars['http_user_agent'], time(), $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload'], strip_tags($origname)));
								
						
								
								$uploadinfo[]['result'] = $filename; 	
								
								unset($origname, $filetitle, $filename, $extension);
							}
							
							$total_file_uploads++;
						}
					}
				}
			}
			break;
	case "zip_standard":
	case "zip-boxed":
	
		if (strlen($mmhclass->input->file_vars['userfile']['name']) < 1 ) {
			$mmhclass->templ->error($mmhclass->lang['015'], true);
		}
		// THIS MAY CAUSE PHP FATAL ERROR ON SOME SYSTEMS, IF NO PATH MAGIC.MIME FILE IS GIVEN!! THEREFORE DISABLED BY DEFAULT!!
		//elseif(($finfo = finfo_open(FILEINFO_MIME)) != false) {
		//	if(strpos(finfo_file($finfo, $mmhclass->input->file_vars['userfile']['tmp_name']), 'zip') == false) {
		//		finfo_close($finfo); 
		//		$mmhclass->templ->error($mmhclass->lang['101'], true);}}
		// THIS MAY CAUSE PHP FATAL ERROR ON SOME SYSTEMS, IF NO PATH MAGIC.MIME FILE IS GIVEN!! THEREFORE DISABLED BY DEFAULT!!
		if($mmhclass->image->file_extension($mmhclass->input->file_vars['userfile']['name']) != 'zip') {
				$mmhclass->templ->error($mmhclass->lang['101'], true);
		}elseif( $mmhclass->input->file_vars['userfile']['size'] > $mmhclass->info->config['max_filesize']) { 
			$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['103'], $mmhclass->input->file_vars['userfile']['name'], $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize'])), "message");
		}else { 
		require_once "./source/includes/dUnzip2.inc.php";
		$zip_ul = new dUnzip2($mmhclass->input->file_vars['userfile']['tmp_name']);
		$rnd_temp_dir = $mmhclass->info->root_path.'temp/zip_uploads/'.$mmhclass->funcs->random_string(8);
		mkdir($rnd_temp_dir);
		chmod($rnd_temp_dir, 0777);
		
		$zip_ul->debug = false;
		$zip_ul->unzipAll($rnd_temp_dir);
		$zip_ul->__destroy();
		$grab_files = scandir($rnd_temp_dir);
		$total_files = count($grab_files);
		
		foreach ($grab_files as $file) {
			if($file != '.' && $file != '..') {
					$origname = preg_replace('/[^\w\._]+/', '',$mmhclass->image->basename($file));
					$rnd_dir_name = $mmhclass->funcs->rand_dir();
					$filesize = filesize($rnd_temp_dir.'/'.$file);
					$filetitle = strip_tags((strlen($origname) > 40) ? sprintf("%s...", substr($origname, 0, 40)) : $origname);
					$shortened_name = strip_tags((strlen($origname) > 55) ? sprintf("%s", substr($origname, 0, 55)) : $origname);
					$filename = sprintf("%s_%s_%s.%s", $mmhclass->funcs->random_string(4, "0123456789"),  $mmhclass->image->basename($shortened_name, '.'.$mmhclass->image->file_extension($origname)), $mmhclass->funcs->random_string(4),($extension = $mmhclass->image->file_extension($origname)));
					$filename = preg_replace('/(\s+|\%)/', '', $filename);				
					if (in_array($extension, $mmhclass->info->config['file_extensions']) == false) {
						unlink($rnd_temp_dir.'/'.$file);
						$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['002'], $origname, $extension), "message");
					} elseif ($filesize > $mmhclass->info->config['max_filesize']) {
						$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['003'], $origname, $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize'])), "message");
					} elseif ($mmhclass->image->is_image($rnd_temp_dir.'/'.$file) == false) {
						unlink($rnd_temp_dir.'/'.$file);
						$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['006'], $origname), "message");
					} elseif ($mmhclass->input->file_vars['userfile']['error'] > 0) {
						
					} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == true) {
						$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['009'], $filename), "error");
					} elseif (rename($rnd_temp_dir.'/'.$file, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == false) {
						$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['010'], $origname), "error");
					} else {
					if ($mmhclass->input->post_vars['image_resize'] > 0 && $mmhclass->input->post_vars['image_resize'] <= 8) {
						rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'rz_'.$filename);
						$mmhclass->image->create_thumbnail($filename, true, $mmhclass->input->post_vars['image_resize'], $rnd_dir_name);
					}
			// BEGIN WATERMARK MOD
					if($mmhclass->info->config['watermark'] == '1') {
						$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
						rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'wm_'.$filename);
						$mmhclass->image->watermark($filename, $rnd_dir_name);
					}
					if($mmhclass->info->config['watermark'] == '0') {
						$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
					}
			// END WATERMARK MOD								
					chmod($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, 0644);
								
					$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `total_rating`, `total_votes`, `voted_by`, `gallery_id`, `is_private`) VALUES ('[2]', '0', '0', '', '[3]', '[4]');", array(MYSQL_FILE_RATINGS_TABLE, $filename, $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload']));
					$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `is_private`, `gallery_id`, `file_title`, `album_id`, `dir_name`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]'); ", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->input->post_vars['private_upload'], $mmhclass->info->user_data['user_id'], $filetitle, $mmhclass->input->post_vars['upload_to'], $rnd_dir_name));																																							
					$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `filesize`, `ip_address`, `user_agent`, `time_uploaded`, `gallery_id`, `is_private`, `original_filename`, `upload_type`, `bandwidth`, `image_views`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]', '[8]', '[9]', 'normal', '[3]', '1'); ", array(MYSQL_FILE_LOGS_TABLE, $filename, $filesize, $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->server_vars['http_user_agent'], time(), $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload'], strip_tags($origname)));
												
					$uploadinfo[]['result'] = $filename; 	
								
					unset($origname, $filetitle, $filename, $extension);
					
			$total_file_uploads++;
			}
		}
	}}
	rmdir($rnd_temp_dir);
	break;
			
	case "ulfy_standard":
	case "ulfy_boxed":
            if (!empty($mmhclass->input->post_vars['session'])) {
		$sql = $mmhclass->db->query("SELECT * FROM `[1]` WHERE `session_key` = '[2]';", array(MYSQL_ULFY_TABLE,$mmhclass->input->post_vars['session']));
		
			if ($mmhclass->db->total_rows($sql) < 1) {
		 		$mmhclass->templ->error($mmhclass->lang['014'], true);
		 	} else {
				while ($row = $mmhclass->db->fetch_array($sql)) {
						
			
					$origname = htmlspecialchars($row['image_name']);
					$filetitle = strip_tags((strlen($origname) > 40) ? sprintf("%s...", substr($origname, 0, 40)) : $origname);
					$shortened_name = strip_tags((strlen($origname) > 55) ? sprintf("%s", substr($origname, 0, 55)) : $origname);
					$filename = sprintf("%s_%s_%s.%s", $mmhclass->funcs->random_string(4, "0123456789"),  $mmhclass->image->basename($shortened_name, '.'.$mmhclass->image->file_extension($origname)), $mmhclass->funcs->random_string(4),($extension = $mmhclass->image->file_extension($origname)));
					$rnd_dir_name = $mmhclass->funcs->rand_dir();		
							if (in_array($extension, $mmhclass->info->config['file_extensions']) == false) {
								unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']);
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['002'], $origname, $extension), "message");
							} elseif ($row['size'] > $mmhclass->info->config['max_filesize']) {
								unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']);
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['003'], $origname, $mmhclass->image->format_filesize($mmhclass->info->config['max_filesize'])), "message");
							} elseif ($mmhclass->image->is_image($mmhclass->info->root_path.'temp/'.$row['tmp_name']) == false) {
								unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']);
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['006'], $origname), "message");
							} elseif ($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == true) {
								unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']);
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['009'], $filename), "error");
							} elseif (copy($mmhclass->info->root_path.'temp/'.$row['tmp_name'], $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == false) {
								unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']);
								$uploadinfo[]['error'] = array(sprintf($mmhclass->lang['010'], $origname), "error");
							} elseif (unlink($mmhclass->info->root_path.'temp/'.$row['tmp_name']) == false) {
									echo 'DIE';
							} else {
								
								if ($mmhclass->input->post_vars['image_resize'] > 0 && $mmhclass->input->post_vars['image_resize'] <= 8) {
									rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'rz_'.$filename);
									$mmhclass->image->create_thumbnail($filename, true, $mmhclass->input->post_vars['image_resize'], $rnd_dir_name);
								}
			// BEGIN WATERMARK MOD
							if($mmhclass->info->config['watermark'] == '1') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
								rename($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, $mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.'wm_'.$filename);
								$mmhclass->image->watermark($filename, $rnd_dir_name);
							}
							if($mmhclass->info->config['watermark'] == '0') {
								$mmhclass->image->create_thumbnail($filename, true, 0, $rnd_dir_name);
							}
			// END WATERMARK MOD								
								chmod($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename, 0644);
								
								$mmhclass->db->query("DELETE FROM `[1]` WHERE `session_key` = '[2]' AND `tmp_name` = '[3]';", array(MYSQL_ULFY_TABLE, $mmhclass->input->post_vars['session'], $row['tmp_name']));
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `total_rating`, `total_votes`, `voted_by`, `gallery_id`, `is_private`) VALUES ('[2]', '0', '0', '', '[3]', '[4]');", array(MYSQL_FILE_RATINGS_TABLE, $filename, $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload']));
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `is_private`, `gallery_id`, `file_title`, `album_id`, `dir_name`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]'); ", array(MYSQL_FILE_STORAGE_TABLE, $filename, $mmhclass->input->post_vars['private_upload'], $mmhclass->info->user_data['user_id'], $filetitle, $mmhclass->input->post_vars['upload_to'], $rnd_dir_name));																																							
								$mmhclass->db->query("INSERT INTO `[1]` (`filename`, `filesize`, `ip_address`, `user_agent`, `time_uploaded`, `gallery_id`, `is_private`, `original_filename`, `upload_type`, `bandwidth`, `image_views`) VALUES ('[2]', '[3]', '[4]', '[5]', '[6]', '[7]', '[8]', '[9]', 'normal', '[3]', '1'); ", array(MYSQL_FILE_LOGS_TABLE, $filename, $row['size'], $mmhclass->input->server_vars['remote_addr'], $mmhclass->input->server_vars['http_user_agent'], time(), $mmhclass->info->user_data['user_id'], $mmhclass->input->post_vars['private_upload'], strip_tags($origname)));
								
						
								
								$uploadinfo[]['result'] = $filename; 	
								
								unset($origname, $filetitle, $filename, $extension);
							}
							
							$total_file_uploads++;
						}
					}
			}
			else {
			$mmhclass->templ->error($mmhclass->lang['014'], true);
			}
		break;		
	}
	
	if (in_array($mmhclass->input->post_vars['upload_type'], array("standard", "url-standard", "ulfy_standard", "zip_standard")) == true) {
		if ($mmhclass->funcs->is_null($uploadinfo) == false) {
			$mmhclass->templ->html = NULL;
			foreach ($uploadinfo as $id => $value) {
				$mmhclass->templ->html .= (($total_file_uploads > 1 && $id !== 0) ? "<hr />" : NULL);
				$mmhclass->templ->html .= ((is_array($uploadinfo[$id]['error']) == true) ? $mmhclass->templ->$uploadinfo[$id]['error']['1']($uploadinfo[$id]['error']['0'], false) : $mmhclass->templ->file_results($uploadinfo[$id]['result']));
			}
		}
	} else {
		if ($mmhclass->funcs->is_null($uploadinfo) == false) {
			foreach ($uploadinfo as $id => $value) {
				if (is_array($uploadinfo[$id]['error']) == false) {
					$mmhclass->templ->templ_globals['uploadinfo'][] = $uploadinfo[$id]['result'];
				} else {
					$mmhclass->templ->templ_globals['errorinfo'][] = $uploadinfo[$id]['error']['0'];
				}
			}
		
			if ($mmhclass->funcs->is_null($mmhclass->templ->templ_globals['uploadinfo']) == false) {
		 // BEGIN SHORT_URL MOD
				$subt = 0;
				if ($mmhclass->info->config['hotlink'] == '1') {
					$subt = 1;
				}
				if ($mmhclass->info->config['shortener'] != 'none') {
					for ($i = 1 - $subt; $i < 8; $i++) {
						foreach ($mmhclass->templ->templ_globals['uploadinfo'] as $filename) {
							$mmhclass->templ->templ_globals['get_whileloop']["uploadinfo_whileloop_{$i}"] = true;
							$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
							$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);
							$mmhclass->templ->templ_vars[] = array(
							"FILENAME" => $filename,
							"BASE_URL" => $mmhclass->info->base_url,
							"SITE_NAME" => $mmhclass->info->config['site_name'],
							"UPLOAD_PATH" => $mmhclass->info->config['upload_path'].$rnd_dir_name,
							"THUMBNAIL" => (($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']) == false) ? "{$mmhclass->info->base_url}css/images/no_thumbnail.png" : $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']),
							"SHORT_URL" => $mmhclass->funcs->shortURL($mmhclass->info->base_url. $mmhclass->info->config['upload_path'].$rnd_dir_name.$filename),
							"SHORT_VIEWER" => $mmhclass->funcs->shortURL($mmhclass->info->base_url.'viewer.php?file='.$filename),
							);
                  
							$mmhclass->templ->templ_globals["uploadinfo_whileloop_{$i}"] .= $mmhclass->templ->parse_template("upload", "boxed_file_results");
							unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $thumbnail);      
							}
					}
				}
				if ($mmhclass->info->config['shortener'] == 'none') {
					for ($i = 1- $subt; $i < 6; $i++) {
						foreach ($mmhclass->templ->templ_globals['uploadinfo'] as $filename) {
						$mmhclass->templ->templ_globals['get_whileloop']["uploadinfo_whileloop_{$i}"] = true;
						$rnd_dir_name = $mmhclass->funcs->rand_dir($check = true, $filename);
						$file_info = $mmhclass->image->get_image_info($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);
						$mmhclass->templ->templ_vars[] = array(
						"FILENAME" => $filename,
						"BASE_URL" => $mmhclass->info->base_url,
						"SITE_NAME" => $mmhclass->info->config['site_name'],
						"UPLOAD_PATH" => $mmhclass->info->config['upload_path'].$rnd_dir_name,
						"THUMBNAIL" => (($mmhclass->funcs->file_exists($mmhclass->info->root_path.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']) == false) ? "{$mmhclass->info->base_url}css/images/no_thumbnail.png" : $mmhclass->info->base_url.$mmhclass->info->config['upload_path'].$rnd_dir_name.$file_info['thumbnail']),
						);
                  
						$mmhclass->templ->templ_globals["uploadinfo_whileloop_{$i}"] .= $mmhclass->templ->parse_template("upload", "boxed_file_results");
						unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $thumbnail);      
						}
					}            
   // END SHORT_URL MOD
						
						$mmhclass->templ->templ_globals["uploadinfo_whileloop_{$i}"] .= $mmhclass->templ->parse_template("upload", "boxed_file_results");
						unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars, $thumbnail);		
					}
				
				
				foreach ($mmhclass->templ->templ_globals['uploadinfo'] as $filename) {
					$break_line = (($tdcount >= 4) ? true : false);
					$tdcount = (($tdcount >= 4) ? 0 : $tdcount);
					$tdcount++;
					
					$mmhclass->templ->templ_vars[] = array(
						"FILENAME" => $filename,
						"FILE_TITLE" => $filename,
						"TABLE_BREAK" => (($break_line == true) ? "</tr><tr>" : NULL),
						"TDCLASS" => $tdclass = (($tdclass == "tdrow1") ? "tdrow2" : "tdrow1"),
					);
					
					$gallery_html .= $mmhclass->templ->parse_template("global", "global_gallery_layout");
					unset($mmhclass->templ->templ_vars, $break_line);	
				}
			}
			
			if ($mmhclass->funcs->is_null($mmhclass->templ->templ_globals['errorinfo']) == false) {
				foreach ($mmhclass->templ->templ_globals['errorinfo'] as $errmsg) {
					$mmhclass->templ->templ_globals['get_whileloop']['errorinfo_whileloop'] = true;
					$mmhclass->templ->templ_vars[] = array("ERROR_MESSAGE" => $errmsg['0']);
					$mmhclass->templ->templ_globals['errorinfo_whileloop'] .= $mmhclass->templ->parse_template("upload", "boxed_file_results");
					unset($mmhclass->templ->templ_globals['get_whileloop'], $mmhclass->templ->templ_vars);	
				}
			}
			
			$mmhclass->templ->templ_vars[] = array(
				"GALLERY_HTML" => $gallery_html,
				"BASE_URL" => $mmhclass->info->base_url,
				"SITE_NAME" => $mmhclass->info->config['site_name'],
			);
			
			$mmhclass->templ->output("upload", "boxed_file_results");
		}
	}
	
	if ($total_file_uploads < 1 && $mmhclass->funcs->is_null($mmhclass->templ->templ_globals['errorinfo']) == true) {
		$mmhclass->templ->error($mmhclass->lang['014'], true);
	} else {	
		if (in_array($mmhclass->input->post_vars['upload_type'], array("standard", "url-standard", "ulfy_standard", "zip_standard")) == true) {
			$mmhclass->templ->output();
		}
	}

?>
