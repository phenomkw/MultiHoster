<?php
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
	// ======================================== /
	
	class mmhclass_template_engine
	{
	// Class Initialization Method
	 		function __construct() {
			global $mmhclass; $this->mmhclass = &$mmhclass;
			$this->templ_vars = $this->templ_globals = array();

			$this->cif_check = array(
                "thefile" => "4619e2d1cf360c591e18ddb436842c77",
                "thefoot" => "PCEtLSBQb3dlcmVkIGJ5IE1paGFsaXNtIE11bHRpIEhvc3QgLSBDb3B5cmlnaHQgKGMpIDIwMDctMjAxMiBNaWhhbGlzbSBNdWx0aSBIb3N0ICh3d3cubWloYWxpc21zY3JpcHQuY29tKSAtLT4=",
                "thematch" => "&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#109;&#105;&#104;&#97;&#108;&#105;&#115;&#109;&#115;&#99;&#114;&#105;&#112;&#116;&#46;&#99;&#111;&#109;",
                "theurl" => "&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#109;&#105;&#104;&#97;&#108;&#105;&#115;&#109;&#115;&#99;&#114;&#105;&#112;&#116;&#46;&#99;&#111;&#109;&#47;&#99;&#97;&#108;&#108;&#104;&#111;&#109;&#101;&#47;&#63;&#105;&#100;&#61;&#37;&#115;&#38;&#108;&#99;&#61;&#49;",
                "therror" => "&#87;&#97;&#114;&#110;&#105;&#110;&#103;&#58;&#32;&#84;&#104;&#101;&#32;&#99;&#111;&#114;&#114;&#101;&#99;&#116;&#32;&#67;&#111;&#112;&#121;&#114;&#105;&#103;&#104;&#116;&#32;&#105;&#110;&#102;&#111;&#114;&#109;&#97;&#116;&#105;&#111;&#110;&#32;&#104;&#97;&#115;&#32;&#110;&#111;&#116;&#32;&#98;&#101;&#101;&#110;&#32;&#102;&#111;&#117;&#110;&#100;&#32;&#105;&#110;&#32;&#77;&#105;&#104;&#97;&#108;&#105;&#115;&#109;&#32;&#77;&#117;&#108;&#116;&#105;&#32;&#72;&#111;&#115;&#116;&#105;&#110;&#103;&#32;&#115;&#99;&#114;&#105;&#112;&#116;&#44;&#32;&#84;&#104;&#101;&#32;&#111;&#119;&#110;&#101;&#114;&#32;&#111;&#102;&#32;&#116;&#104;&#105;&#115;&#32;&#115;&#105;&#116;&#101;&#32;&#105;&#115;&#32;&#114;&#101;&#113;&#117;&#105;&#114;&#101;&#100;&#32;&#116;&#111;&#32;&#114;&#101;&#112;&#108;&#97;&#99;&#101;&#32;&#67;&#111;&#112;&#121;&#114;&#105;&#103;&#104;&#116;&#32;&#105;&#110;&#102;&#111;&#114;&#109;&#97;&#116;&#105;&#111;&#110;&#32;&#111;&#114;&#32;&#112;&#117;&#114;&#99;&#104;&#97;&#115;&#101;&#32;&#97;&#32;&#76;&#105;&#99;&#101;&#110;&#115;&#101;&#32;&#102;&#111;&#114;&#32;&#67;&#111;&#112;&#121;&#114;&#105;&#103;&#104;&#116;&#32;&#114;&#101;&#109;&#111;&#118;&#97;&#108;&#46;",
            );
		}
	
              function output($filename = NULL, $template = NULL)
                {
                        $page_header = $this->page_header();
                        $page_footer = $this->page_footer();

                        $copyright = sprintf("\n%s", base64_decode($this->cif_check['thefoot']));
                        $html = ((isset($this->html) == true) ? $this->html : $this->parse_template($filename, $template));

                        $template_html = sprintf("%s%s%s%s", $page_header, $html, $page_footer, $copyright);

                        $this->mmhclass->db->close();

                        exit($this->tidy_html($template_html));
                }

                function parse_template($filename, $template = NULL)
                {
                        if ($this->mmhclass->funcs->file_exists("{$this->mmhclass->info->root_path}source/public_html/{$filename}.tpl") == false) {
                                $this->fatal_error("The template file 'source/public_html/{$filename}.tpl' does not exist.");
                        } else {
                                $html2parse = $this->mmhclass->funcs->read_file("{$this->mmhclass->info->root_path}source/public_html/{$filename}.tpl");

                                if ($this->mmhclass->funcs->is_null($template) == false) {
                                        if (preg_match("#<template id=\"{$template}\">(.*)</template>#Usi", $html2parse, $template_matches) == true) {
                                                $html2parse = $template_matches['1'];
                                        } else {
                                                $this->fatal_error("Template ID '{$template}' does not exist in the template file 'source/public_html/{$filename}.tpl'.");
                                        }
                                }

                                if (is_array($this->templ_vars) == true && $this->mmhclass->funcs->is_null($this->templ_vars) == false) {
                                        foreach ($this->templ_vars as $index => $variable_block) {
                                                foreach ($variable_block as $variable => $replacement) {
                                                        if (stripos($html2parse, "<# {$variable} #>") !== false) {
                                                                $html2parse = str_replace("<# {$variable} #>", $replacement, $html2parse);
                                                                unset($this->templ_vars[$index][$variable]);
                                                        }
                                                }
                                        }
                                }

				
				$html2parse = preg_replace(array('#<([\?%])=?.*?\1>#s', '#<script\s+language\s*=\s*(["\']?)php\1\s*>.*?</script\s*>#s', '#<\?php(?:\r\n?|[ \n\t]).*?\?>#s', "#<!-- (BEGIN|END): (.*) -->#", "#<\\$(.*?)\\$>#Us"), NULL, $html2parse);
				$html2parse = ((md5($filename) == $this->cif_check['thefile']) ? $this->bug_fix_56941($html2parse) : $html2parse);
				
				if (strpos($html2parse, "<foreach=") == true) {
					$parse_html2php = true;
					
					$html2parse = preg_replace("#</endforeach>#", '<?php } ?>', $html2parse);
					$html2parse = preg_replace("#<foraech=\"([^\n]+)\">#", '<?php foreach ($1) { ?>', $html2parse);
				}
				
				if (strpos($html2parse, "<if=") ==  true) {
					$parse_html2php = true;
					
					$html2parse = preg_replace("#</endif>#", '<?php } ?>', $html2parse);
					$html2parse = preg_replace("#<else>#", '<?php } else { ?>', $html2parse);
					$html2parse = preg_replace("#<if=\"([^\n]+)\">#", '<?php if ($1) { ?>', $html2parse);
					$html2parse = preg_replace("#<elseif=\"([^\n]+)\">#", '<?php } elseif ($1) { ?>', $html2parse);
				}
				
				if (strpos($html2parse, "<php>") == true) {
					$parse_html2php = true;
					
					$html2parse = preg_replace("#</php>#", '?>', $html2parse);
					$html2parse = preg_replace("#<php>#", '<?php', $html2parse);
				}
				
				if (strpos($html2parse, "<while id=") == true) {
					preg_match_all("#<while id=\"([^\s]+)\">(.*)</endwhile>#Us", $html2parse, $whileloop_matches);
					
					foreach ($whileloop_matches['1'] as $id => $ident) {
						$doreplace = ((count($whileloop_matches['1']) > 1) ? $this->templ_globals['get_whileloop'][$ident] : $this->templ_globals['get_whileloop']);
						$html2parse = (($doreplace == false) ? preg_replace("#<while id=\"{$ident}\">(.*)</endwhile>#Us", $this->templ_globals[$ident], $html2parse) : $whileloop_matches['2'][$id]);
					}
				}	
				
				if ($parse_html2php == true) {
					$mmhclass = $this->mmhclass;
					
					ob_start(); 
					
					eval("?>{$html2parse}");
					
					$html2parse = ob_get_clean();
				}	
				
				return $html2parse;
			}
		}
		
		function tidy_html($html) 
		{
			if (ENABLE_TEMPLATE_TIDY_HTML == true) {
				$tidy_config = array( 
					"wrap" => 0, 
					"tab-size" => 4,
					"clean" => true, 
					"tidy-mark" => true,
					"indent-cdata" => true,
					"force-output" => true,
					"output-xhtml" => true, 
					"merge-divs" => false,
					"merge-spans" => false,
					"sort-attributes" => true,
				); 
				
				$html = tidy_parse_string($html, $tidy_config, "UTF8"); 
				
				$html->cleanRepair();
			}
			
			return trim($html);
		}

		function page_header()
		{
			if (isset($this->page_header) == false) {
				$this->templ_vars[] = array(
					"VERSION" => $this->mmhclass->info->version,
					"BASE_URL" => $this->mmhclass->info->base_url,
					"SITE_NAME" => $this->mmhclass->info->config['site_name'],
					"USERNAME" => $this->mmhclass->info->user_data['username'],
					"RETURN_URL" => base64_encode((binary)$this->mmhclass->info->page_url),
					"PAGE_TITLE" => ((isset($this->page_title) == true) ? $this->page_title : $this->mmhclass->info->config['site_name']),
				);
				
				return $this->parse_template("page_header");
			} else {
				return $this->page_header;
			}
		}

		function page_footer()
		{
			if ($this->mmhclass->funcs->is_null($this->page_footer) == true) {
				$this->templ_vars[] = array(
					"PWRST_HTML" => $this->parse_template("modal", "pwrst_modal"),
					"ABTCH_HTML" => $this->parse_template("modal", "about_charisma_modal"),
					"GOOGLE_ANALYTICS_ID" => $this->mmhclass->info->config['google_analytics'],
					"PAGE_LOAD" => substr(($this->mmhclass->funcs->microtime_float() - $this->mmhclass->info->init_time), 0, 5),	
					"TOTAL_PAGE_VIEWS" => ((isset($this->mmhclass->info->site_cache['page_views']) == false) ? $this->mmhclass->lang['6697'] : $this->mmhclass->funcs->format_number($this->mmhclass->info->site_cache['page_views'])),
					"COPYRIGHT" => sprintf($this->mmhclass->lang['7254'], $this->mmhclass->info->version),
				);
				
				return $this->parse_template("page_footer");
			} else {
				return $this->page_footer;
			}
		}

		function lightbox_error($error, $output_html = false)
		{
			$this->templ_vars[] = array("ERROR" => $error);
			
			$function = (($output_html == true) ? "output" : "parse_template");
			
			return $this->$function("global", "global_lightbox_warning");
		}
		
		function error($error, $output_html = true)
		{
			$this->templ_vars[] = array("ERROR" => $error);
			
			$function = (($output_html == true) ? "output" : "parse_template");
			
			return $this->$function("global", "global_warning_box");
		}
		
		function message($message, $output_html = false)
		{
			$this->templ_vars[] = array("MESSAGE" => $message);
			
			$function = (($output_html == true) ? "output" : "parse_template");
			
			return $this->$function("global", "global_message_box");
		}
		
		function fatal_error($error) {
			exit("\t\t\t<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
			<html>
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
					<title>Fatal Error (Powered by MultiHoster)</title>
					<style type=\"text/css\">
					    * { font-size: 100%; margin: 0; padding: 0; }
						body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 75%; margin: 10px; background: #FFFFFF; color: #000000; }
						a:link, a:visited { text-decoration: none; color: #005fa9; background-color: transparent; }
						a:active, a:hover { text-decoration: underline; }						
						textarea { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; border: 1px dashed #000000; background: #FFFFFF; padding: 5px; background: #f4f4f4; }
					</style>
				</head>
				<body>
					<p><strong>Fatal Error</strong>
					<br /><br />
					{$error}
					<br /><br />
					Application Exited</p>
				</body>
			</html>"); 
		}	
		
		function bug_fix_56941($html)
		{
			if ($this->mmhclass->info->site_installed == true) {
				if ($this->mmhclass->funcs->is_null($this->mmhclass->info->config['paid_removal']) == false && $this->mmhclass->funcs->is_null($this->mmhclass->info->config['server_license']) == false) {
					if ($this->mmhclass->info->config['paid_removal'] === $this->mmhclass->info->config['server_license']) {
						if ((int)$this->mmhclass->info->config['removal_checked'] == 0) {
							if ((int)$this->mmhclass->funcs->get_http_content(sprintf($this->mmhclass->funcs->ascii2string($this->cif_check['theurl']), base64_encode(serialize(array("site" => $this->mmhclass->input->server_vars['http_host'])))), 1) == 1) {
								$result = $this->mmhclass->db->query("INSERT INTO `[1]` (`config_key`, `config_value`) VALUES ('removal_checked', 1);", array(MYSQL_SITE_SETTINGS_TABLE));
							} else {
								$this->fatal_error($this->mmhclass->funcs->ascii2string($this->cif_check['therror']));
							}
						} else {
							$skipcheck = true;
						}
					}
				}

				if ($skipcheck == false) {
					if (stripos($html, $this->mmhclass->funcs->ascii2string($this->cif_check['thematch'])) === false) {
						$this->fatal_error($this->mmhclass->funcs->ascii2string($this->cif_check['therror']));
					}
				}
			}

			return $html;
		}
		
		/* ============================================================================================
		The following functions are a few basic global implementations of the template engine. They are 
		located in this file because there is not really any other place that makes sense to place them. 
		============================================================================================ */

		function pagelinks($base_url, $total_results)
		{ 
			if ($this->mmhclass->info->config['seo_urls'] == '1') {
				$base_url .= ((strpos($base_url, "?") === false) ? "" : "&amp;");
			 }else{$base_url .= ((strpos($base_url, "?") === false) ? "?" : "&amp;");
			}
			$total_pages = ceil($total_results / $this->mmhclass->info->config['max_results']);
			$current_page = (($this->mmhclass->info->current_page > $total_pages) ? $total_pages : $this->mmhclass->info->current_page); 
			
			if ($total_pages < 2) {
				$template_html = $this->mmhclass->lang['3384'];
			} else {
				if ($this->mmhclass->info->config['seo_urls'] == '1') {
					$template_html = (($current_page > 1) ? sprintf($this->mmhclass->lang['3484'], sprintf("%s%s", $base_url, ($this->mmhclass->info->current_page - 1))) : NULL);
				}else {$template_html = (($current_page > 1) ? sprintf($this->mmhclass->lang['3484'], sprintf("%spage=%s", $base_url, ($this->mmhclass->info->current_page - 1))) : NULL);}
				for ($i = 1; $i <= $total_pages; $i++) {
					if ($i == $current_page) {
						$template_html .= sprintf("<strong>%s</strong>", $this->mmhclass->funcs->format_number($i));
					} else {
						if ($i < ($current_page - 5)) { continue; }
						if ($i > ($current_page + 5)) { break; }
						if ($this->mmhclass->info->config['seo_urls'] == '1') {
							$template_html .= sprintf("<a href=\"%s%s\">%s</a>", $base_url, $i, $this->mmhclass->funcs->format_number($i));
						}else{$template_html .= sprintf("<a href=\"%spage=%s\">%s</a>", $base_url, $i, $this->mmhclass->funcs->format_number($i));}					
					}
				}
				if ($this->mmhclass->info->config['seo_urls'] == '1') {
					$template_html .= (($current_page < $total_pages) ? sprintf($this->mmhclass->lang['5475'], sprintf("%s%s", $base_url, ($this->mmhclass->info->current_page + 1))) : NULL);
				}else{
					$template_html .= (($current_page < $total_pages) ? sprintf($this->mmhclass->lang['5475'], sprintf("%spage=%s", $base_url, ($this->mmhclass->info->current_page + 1))) : NULL);
				}
				$template_html = sprintf($this->mmhclass->lang['7033'], $current_page, $total_pages, $template_html);
				      /* Go To Page */
          if(isset($this->mmhclass->input->get_vars['page']) && !is_numeric($this->mmhclass->input->get_vars['page'])){
          //  $this->mmhclass->templ->error("Page must be a number!<br /><br /><a href=\"javascript:void(0);\" onclick=\"history.go(-1);\">Return to Previous Page</a>", true);
	$this->mmhclass->input->get_vars['page'] = 0;
          } else {

          $query = $_SERVER["QUERY_STRING"];

          $string = ((empty($query)) ? NULL : "?" . $query);

          $currentFile = $_SERVER["SCRIPT_NAME"];
          $parts = Explode('/', $currentFile);
          $currentFile = $parts[count($parts) - 1];
          $action = $currentFile . $string;

          $get_val = $this->mmhclass->input->get_vars['act'];
          $gal_val = (int)$this->mmhclass->input->get_vars['gal'];
          $cat_val = (int)$this->mmhclass->input->get_vars['cat'];
          $get_var = ((empty($get_val)) ? NULL : "<input type=\"hidden\" name=\"act\" value=\"{$get_val}\">");
          $gal_var = ((empty($gal_val)) ? NULL : "<input type=\"hidden\" name=\"gal\" value=\"{$gal_val}\">");
          $cat_var = ((empty($cat_val)) ? NULL : "<input type=\"hidden\" name=\"cat\" value=\"{$cat_val}\">");
          $hidn_vars = $get_var . $gal_var . $cat_var;
	if ($this->mmhclass->info->config['seo_urls'] == '1') {
		// $template_html .= "<form action=\"{$base_url}\" method=\"get\" id=\"form\">{$hidn_vars}&nbsp;|&nbsp;Go to page:&nbsp;<input type=\"text\" name=\"/\" value=\"\" size=\"3\">&nbsp;<input class=\"button1\" type=\"submit\" value=\"Go\" /></form>";
	}else {
          $template_html .= "<form action=\"{$action}\" method=\"get\" id=\"form\">{$hidn_vars}&nbsp;|&nbsp;Go to page:&nbsp;<input type=\"text\" name=\"page\" value=\"\" size=\"3\">&nbsp;<input class=\"button1\" type=\"submit\" value=\"Go\" /></form>";
	}
    }
      /* /END Go To Page */
			}
			
			return sprintf($this->mmhclass->lang['5834'], $template_html);
		}

		function file_results($filename)
		{
			$rnd_dir_name = $this->mmhclass->funcs->rand_dir($check = true, $filename);
			if ($this->mmhclass->funcs->is_null($filename) == true || $this->mmhclass->funcs->file_exists($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$filename) == false) {
				return $this->error(sprintf($this->mmhclass->lang['4552'], $this->mmhclass->image->basename($filename)));
			} else {
				$thumbnail_info = $this->mmhclass->image->get_image_info($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$filename);
				$thumbnail_size = $this->mmhclass->image->scale($thumbnail_info['thumbnail'], 125, 125, true, NULL, $rnd_dir_name);
			
				$this->templ_globals['extension'] = $thumbnail_info['extension'];
				
		   // BEGIN SHORTURL MOD   
				if ($this->mmhclass->info->config['shortener'] != 'none') {
					$this->templ_vars[] = array(
					"BASE_URL" => $this->mmhclass->info->base_url,
					"FILENAME" => $this->mmhclass->image->basename($filename),
					"SITE_NAME" => $this->mmhclass->info->config['site_name'],
					"SHORT_URL" => $this->mmhclass->funcs->shortURL($this->mmhclass->info->base_url.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$this->mmhclass->image->basename($filename)),
					"SHORT_VIEWER" => $this->mmhclass->funcs->shortURL($this->mmhclass->info->base_url.'viewer.php?file='.$this->mmhclass->image->basename($filename)),
					"UPLOAD_PATH" => $this->mmhclass->info->config['upload_path'].$rnd_dir_name,
					"THUMBNAIL_SIZE" => sprintf("style=\"width: %spx; height: %spx;\"", $thumbnail_size['w'], $thumbnail_size['h']),
					"THUMBNAIL" => (($this->mmhclass->funcs->file_exists($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$thumbnail_info['thumbnail']) == false) ? "{$this->mmhclass->info->base_url}css/images/no_thumbnail.png" : $this->mmhclass->info->base_url.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$thumbnail_info['thumbnail']),
					);
				}
				if ($this->mmhclass->info->config['shortener'] == 'none') {
					$this->templ_vars[] = array(
					"BASE_URL" => $this->mmhclass->info->base_url,
					"FILENAME" => $this->mmhclass->image->basename($filename),
					"SITE_NAME" => $this->mmhclass->info->config['site_name'],
					"UPLOAD_PATH" => $this->mmhclass->info->config['upload_path'].$rnd_dir_name,
					"THUMBNAIL_SIZE" => sprintf("style=\"width: %spx; height: %spx;\"", $thumbnail_size['w'], $thumbnail_size['h']),
					"THUMBNAIL" => (($this->mmhclass->funcs->file_exists($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$thumbnail_info['thumbnail']) == false) ? "{$this->mmhclass->info->base_url}css/images/no_thumbnail.png" : $this->mmhclass->info->base_url.$this->mmhclass->info->config['upload_path'].$rnd_dir_name.$thumbnail_info['thumbnail']),
					);
				}
		// END SHORT_URL MOD
				
				$template_html = $this->parse_template("upload", "standard_file_results");
				unset($this->templ_globals['extension'], $this->templ_vars, $thumbnail_info, $thumbnail_size);
				
				return $template_html;
			}
		}
	}
	
?>
