<?php
	// ======================================== \
	// Package: Mihalism Multi Host
	// Version: 5.0.0
	// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1254250993 - Tuesday, September 29, 2009, 03:03:13 PM EDT -0400
	// ======================================== /
	
	class mmhclass_image_functions
	{
		// Class Initialization Method
		function __construct() { global $mmhclass; $this->mmhclass = &$mmhclass; }
	// Extended check to ignore ? tags after image-extension	
		function file_extension($filename)
		{
			$fileparts = explode(".", $filename);
			$more_check = strpos(strtolower(end($fileparts)), '?');
			if ($more_check  != false) {
				
				return substr(strtolower(end($fileparts)),0, $more_check );
		}
		else {
			
			return strtolower(end($fileparts));
		}
		}
	// Extended check to ignore ? tags after image-extension	END	
		function basename($filename, $extension = NULL)
		{
			if (is_array($filename) == false) {
				return strtolower(basename(trim($filename), $extension));
			} else {
				return array_map("strtolower", array_map("basename", array_map("trim", $filename), array($extension)));	
			}
		}
		
		function is_image($filename)
		{
			if ($this->mmhclass->funcs->file_exists($filename) == true) {
				// exif will be best bet to try first
				
				if (EXIF_IS_AVAILABLE == true) {
					if (exif_imagetype($filename) == false) {
						return false;	
					}
				} else {
					// darn exif is not available! 
					// well hopefully imagick is up
					
					if ($this->manipulator == "imagick") {
						// Why can't Imagick just return a false boolean?
						// Catching error exceptions uses unnecessary code
						
						try {
							$imageh = new Imagick("{$filename}[0]");
							
							if ($imageh == false) {
								return true;
							}
						} catch (Exception $e) {
							return false;
						}
					} else {
						// Come on seriously? No exif or Imagick?
						// GD supports like nothing. Oh well :-(
	
						$imageinfo = getimagesize($filename);
						
						if (isset($imageinfo['2']) == false) {
							return false;	
						}
					}
				}
			} else {
				// Well, well, well. Looks like the image doesn't exist.
				
				// Other than for debugging purposes for former support
				// ticket on mihalism.net, no need to log error for this
				
				trigger_error("mmhclass->image->is_image(): image does not exist. ({$filename})", E_USER_ERROR);
				
				return false;
			}
			
			return true;
		}
		
		function thumbnail_name($filename)
		{
			$file_extension = $this->file_extension($filename);
			$base_filename = $this->basename($filename, ".{$file_extension}");
			
			if ($this->manipulator == "gd") {
				return sprintf("%s_thumb.%s", $base_filename, $file_extension);	
			} else {
				// An easier method could most likely be developed 
				// to do this but this will have to do for now :-)
						
				$real_extension = (($this->mmhclass->info->config['thumbnail_type'] == "png") ? "png" : "jpg");
			
				$check_extension = (($this->mmhclass->info->config['thumbnail_type'] == "png") ? "jpg" : "png");
				$check_thumbtype = (($this->mmhclass->info->config['thumbnail_type'] == "png") ? "jpeg" : "png");
				
				$thumbname = sprintf("%s_thumb.%s", $base_filename, $check_extension);
				$file_check = $this->mmhclass->funcs->file_exists($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$thumbname);
				return (($file_check == true || $file_check == false && $this->mmhclass->info->config['thumbnail_type'] == $check_thumbtype) ? $thumbname : sprintf("%s_thumb.%s", $base_filename, $real_extension));
			}
		}
		
		function format_filesize($filesize = 0, $returnbytes = false)
		{
			// Prior to Mihalism Multi Host 5.0.3 this entire function
			// was squeezed into 2 lines. Imagine debugging that. No fun.
			
			while (($filesize / 1024) >= 1) { 
				$filesize_count++; 
				$filesize = ($filesize / 1024); 
			} 
			
			$filesize_count = (($filesize_count < 0) ? 0 : $filesize_count);
			
			if ($returnbytes == true) {
				return array("f" => $filesize, "c" => $filesize_count);
			} else {
				$finalsize = substr($filesize, 0, (strpos($filesize, ".") + 4));
				$finalname = (($filesize > 0.9 && $filesize < 2.0) ? $this->mmhclass->lang['3103'][$filesize_count] : $this->mmhclass->lang['4191'][$filesize_count]);
				
				return (($filesize < 0 || $filesize_count > 9) ? $this->mmhclass->lang['5454'] : sprintf("%s %s", $finalsize, $finalname));
			}
		}
		
		function get_image_info($filename, $querydb = false) 
		{							 
			if ($this->is_image($filename) == false) {
				return false;
			} else {
				$base_filename = $this->basename($filename);
					
				if ($querydb == true) {
					$file_logs = $this->mmhclass->db->fetch_array($this->mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_LOGS_TABLE, $base_filename)));
					$file_sinfo = $this->mmhclass->db->fetch_array($this->mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_STORAGE_TABLE, $base_filename)));
					$rating_info = $this->mmhclass->db->fetch_array($this->mmhclass->db->query("SELECT * FROM `[1]` WHERE `filename` = '[2]' LIMIT 1;", array(MYSQL_FILE_RATINGS_TABLE, $base_filename)));
				}
				
				if ($this->manipulator == "imagick") {
					$imageh = new Imagick("{$filename}[0]");
					
					return array(
						"logs" => $file_logs,
						"sinfo" => $file_sinfo,
						"rating" => $rating_info,
						"filename" => $base_filename, 
						"mtime" => filemtime($filename),
						"type" => $imageh->getImageType(),
						"bits" => $imageh->getImageLength(),
						"width" => $imageh->getImageWidth(),
						"height" => $imageh->getImageHeight(),
						"extension" => $this->file_extension($filename),
						"thumbnail" => $this->thumbnail_name($filename),
						"comment" => $imageh->getImageProperty("comment"),
						"mime" => sprintf("image/%s", strtolower($imageh->getImageFormat())),
						"html" => sprintf("width=\"%spx;\" height=\"%spx;\"", $imageh->getImageWidth(), $imageh->getImageHeight()),
					);
				} else {
					$base_info = getimagesize($filename);
					
					return array(
						"logs" => $file_logs,
						"sinfo" => $file_sinfo,
						"rating" => $rating_info,
						"type" => $base_info['2'],
						"html" => $base_info['3'],
						"width" => $base_info['0'],
						"height" => $base_info['1'],
						"mime" => $base_info['mime'],
						"filename" => $base_filename, 
						"bits" => filesize($filename),
						"mtime" => filemtime($filename),
						"extension" => $this->file_extension($filename),
						"thumbnail" => $this->thumbnail_name($filename),
						"comment" => NULL, // Why do we bother declaring this in GD? GD is not advanced enough for it.
					);
				}
			}
		}
		
		function scale($filename, $width = 500, $height = 500, $fillpath = true, $scaletype = NULL, $rnd_dir) 
		{
			$filename = (($fillpath == false) ? $filename : $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
			
			$imageinfo = $this->get_image_info($filename);
			
			switch ($scaletype) {
				case "maxwidth":
					if ($imageinfo['width'] > $width) {
						if ($imageinfo['width'] > $imageinfo['height']) {
							$image_width = $width;
							$image_height = (($imageinfo['height'] * $width) / $imageinfo['width']);
						} elseif ($imageinfo['width'] < $imageinfo['height']) {
							$image_width = (($imageinfo['width'] * $width) / $imageinfo['height']);
							$image_height = $width;
						} elseif ($imageinfo['height'] == $imageinfo['width']) {
							$image_height = $image_height = $width;
						}
						
						return array("w" => $image_width, "h" => $image_height);
					}
					break;
				case "maxheight":
					if ($imageinfo['height'] > $height) {
						if ($imageinfo['width'] > $height) {
							$image_width = $imageinfo['width'];
							$image_height = (($height * $height) / $imageinfo['width']);
						} elseif ($imageinfo['width'] < $height) {
							$image_width = (($imageinfo['width'] * $imageinfo['width']) / $height);
							$image_height = $height;
						} elseif ($height == $imageinfo['width']) {
							$image_width = $imageinfo['width'];
							$image_height = $height;
						}
						
						return array("w" => $image_width, "h" => $image_height);
					}
					break;
				default:
					if ($imageinfo['width'] > $width || $imageinfo['height'] > $height) {
						$get_ratio = $imageinfo['width'] / $imageinfo['height'];
						if ($imageinfo['width'] > $imageinfo['height']) {
							$image_width = $width;
							$image_height = round($image_width / $get_ratio);
						} elseif ($imageinfo['width'] < $imageinfo['height']) {
							$image_height = $height;
							$image_width = round($image_height * $get_ratio);
						} elseif ($imageinfo['height'] == $imageinfo['width']) {
							if ($imageinfo['width'] > $width)  {
								$image_width = $width;
								$image_height = $width;
							}
							if  ($imageinfo['height'] > $height) {
								$image_height = $height;
								$image_width = $height;
							}
						}
						
						return array("w" => $image_width, "h" => $image_height);
					}
			}
			
			// No scale returned by now?
			// If not, return something.
			
			return array("w" => $imageinfo['width'], "h" => $imageinfo['height']);
		}
		
		// Only reason I kept the scaleby_* functions was
		// because when I coded scale() I was feeling lazy. :-)
		
		function scaleby_maxwidth($filename, $width = 500, $fillpath = true, $rnd_dir) 
		{
			return $this->scale($filename, $width, NULL, $fillpath, "maxwidth",  $rnd_dir);		
		}
		
		function scaleby_maxheight($filename, $height = 500, $fillpath = true,  $rnd_dir) 
		{
			return $this->scale($filename, NULL, $height, $fillpath, "maxheight",  $rnd_dir);	
		}

		// A lot of code, for thumbnails. 
		
		function create_thumbnail($filename, $save2disk = true, $resize_type = 0, $rnd_dir)
		{	
			if ($resize_type != 0) {
			$imgname = $filename;
			$filename = 'rz_'.$filename;
			}
			
			$filename = $this->basename($filename);
				
			if ($this->is_image($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename) == true) {
				$extension = $this->file_extension($filename);
				$thumbnail = $this->thumbnail_name($filename);
				
				if ($save2disk == true) {
					// Seemed easier to build the image resize upload  
					// option into the already established thumbnail function 
					// instead of waisting time trying to chop it up for new one.
					
					if ($resize_type > 0 && $resize_type <= 8) {
						$thumbnail = $filename;
						
						$this->mmhclass->info->config['advanced_thumbnails'] = false;
					
						$size_values = array(
							1 => array("w" => 100, "h" => 75),
							2 => array("w" => 150, "h" => 112),
							3 => array("w" => 320, "h" => 240),
							4 => array("w" => 640, "h" => 480),
							5 => array("w" => 800, "h" => 600),
							6 => array("w" => 1024, "h" => 768),
							7 => array("w" => 1280, "h" => 1024),
							8 => array("w" => 1600, "h" => 1200),	
						);
						$rz_size = $size_values[$resize_type];
						$thumbnail_size = $this->scale($filename, $rz_size['w'], $rz_size['h'], true, NULL, $rnd_dir);
						
					} else {
						$thumbnail_size = $this->scale($filename, $this->mmhclass->info->config['thumbnail_width'], $this->mmhclass->info->config['thumbnail_height'], true, NULL, $rnd_dir);
					}
					
					if ($this->manipulator == "imagick") {
						// New Design of Advanced Thumbnails created by: IcyTexx - http://www.hostili.com
						// Classic Design of Advanced Thumbnails created by: Mihalism Technologies - http://www.mihalism.net
						
						$canvas = new Imagick();
						$athumbnail = new Imagick();
						
						$imagick_version = $canvas->getVersion();
						
						// Imagick needs to start giving real version number, not build number.
						$new_thumbnails = ((version_compare($imagick_version['versionNumber'], "1621", ">=") == true) ? true : false);
						
						$athumbnail->readImage("{$this->mmhclass->info->root_path}{$this->mmhclass->info->config['upload_path']}{$rnd_dir}{$filename}[0]");
					
						$athumbnail->flattenImages();
						$athumbnail->orgImageHeight = $athumbnail->getImageHeight();
						$athumbnail->orgImageWidth = $athumbnail->getImageWidth();
						$athumbnail->orgImageSize = $athumbnail->getImageLength();
						$athumbnail->thumbnailImage($thumbnail_size['w'], $thumbnail_size['h']);
						
						if ($this->mmhclass->info->config['advanced_thumbnails'] == true) {
							$thumbnail_filesize = $this->format_filesize($athumbnail->orgImageSize, true);
							$resobar_filesize = (($thumbnail_filesize['f'] < 0 || $thumbnail_filesize['c'] > 9) ? $this->mmhclass->lang['5454'] : sprintf("%s%s", round($thumbnail_filesize['f']), $this->mmhclass->lang['7071'][$thumbnail_filesize['c']]));
	
							if ($new_thumbnails == true) {
								$textdraw = new ImagickDraw();
								$textdrawborder = new ImagickDraw();
							
								if ($athumbnail->getImageWidth() > 113) {
									$textdraw->setFillColor(new ImagickPixel("white"));
									$textdraw->setFontSize(9);
									$textdraw->setFont("{$mmhclass->info->root_path}css/fonts/sf_fedora_titles.ttf");
									$textdraw->setFontWeight(900);
									$textdraw->setGravity(8);
									$textdraw->setTextKerning(1);
									$textdraw->setTextAntialias(false);
									
									$textdrawborder->setFillColor(new ImagickPixel("black"));
									$textdrawborder->setFontSize(9);
									$textdrawborder->setFont("{$mmhclass->info->root_path}css/fonts/sf_fedora_titles.ttf");
									$textdrawborder->setFontWeight(900);
									$textdrawborder->setGravity(8);
									$textdrawborder->setTextKerning(1);
									$textdrawborder->setTextAntialias(false);
									
									$array_x = array("-1", "0", "1", "1", "1", "0", "-1", "-1");
									$array_y = array("-1", "-1", "-1", "0", "1", "1", "1", "0");
									
									foreach ($array_x as $key => $value) {
										$athumbnail->annotateImage($textdrawborder, $value, (3 - $array_y[$key]), 0, "{$athumbnail->orgImageWidth}x{$athumbnail->orgImageHeight} - {$resobar_filesize}");
									}

									$athumbnail->annotateImage($textdraw, 0, 3, 0, "{}x{$athumbnail->orgImageHeight} - {$resobar_filesize}");
								}
							} else {
								$transback = new Imagick();
								$canvasdraw = new ImagickDraw();
							
								$canvas->newImage($athumbnail->getImageWidth(), ($athumbnail->getImageHeight() + 12), new ImagickPixel("black"));
								$transback->newImage($canvas->getImageWidth(), ($canvas->getImageHeight() - 12), new ImagickPixel("white"));
								
								$canvas->compositeImage($transback, 40, 0, 0);
								$canvasdraw->setFillColor(new ImagickPixel("white"));
								$canvasdraw->setGravity(8);
								$canvasdraw->setFontSize(10);
								$canvasdraw->setFontWeight(900);
								$canvasdraw->setFont("AvantGarde-Demi");
								$canvas->annotateImage($canvasdraw, 0, 0, 0, "{$athumbnail->orgImageWidth}x{$athumbnail->orgImageHeight} - {$resobar_filesize}");
								$canvas->compositeImage($athumbnail, 40, 0, 0); 
								
								$athumbnail = $canvas->clone();
							}
						}
						if ($resize_type != 0) {
							if ($extension == 'jpg' || $extension == 'jpeg') {
							$athumbnail->setImageFormat("jpeg");
							$athumbnail->setImageCompression(9);
							}
							else {
							$athumbnail->setImageFormat($extension);
							}
						}
						elseif ($resize_type == 0) {
						if ($this->mmhclass->info->config['thumbnail_type'] == "jpeg") {
							$athumbnail->setImageFormat("jpeg");
							$athumbnail->setImageCompression(9);
						} else {	
							$athumbnail->setImageFormat("png"); 
						}
						}
						

						if ($resize_type == 0) {
						$athumbnail->writeImage($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$thumbnail);
						}

						if ($resize_type != 0) {
						$athumbnail->writeImage($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$imgname);
						unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
						}
					} else {
											
						if (in_array($extension, array("png", "gif", "jpg", "jpeg")) == true) {	
							$function_extension = str_replace("jpg", "jpeg", $extension);
							
							$image_function = "imagecreatefrom{$function_extension}";
							$image = $image_function($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
							
							$imageinfo = $this->get_image_info($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$this->basename($filename));
							$thumbnail_image = imagecreatetruecolor($thumbnail_size['w'], $thumbnail_size['h']);
							
							$index = imagecolortransparent($thumbnail_image);
							
							if ($index < 0) {
								$white = imagecolorallocate($thumbnail_image, 255, 255, 255);
								imagefill($thumbnail_image, 0, 0, $white);
							}
							
							imagecopyresampled($thumbnail_image, $image, 0, 0, 0, 0, $thumbnail_size['w'], $thumbnail_size['h'], $imageinfo['width'], $imageinfo['height']);
							if ($resize_type == 0) {
							$image_savefunction = sprintf("image%s", (($this->mmhclass->info->config['thumbnail_type'] == "jpeg") ? "jpeg" : "png"));
							

						
							$image_savefunction($thumbnail_image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$thumbnail);
							chmod($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$thumbnail, 0644);
							}
							if ($resize_type != 0) {
							$image_savefunction = 'image'.$function_extension;
							$image_savefunction($thumbnail_image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$imgname);
							unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);							
							}
							imagedestroy($image);	
							imagedestroy($thumbnail_image); 
						} else {
							trigger_error("Image format not supported by GD", E_USER_ERROR);
						}
					}
					if ($resize_type == 0) {
					chmod($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$thumbnail, 0644);
					}
				} else {
					if ($user_thumb == false) 
					readfile($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$thumbnail);
				}

			}
		}
		  // BEGIN WATERMARK MOD
      function watermark($filename, $rnd_dir)
      {
         if ($this->mmhclass->image->is_image($this->mmhclass->info->root_path.'css/watermark/watermark.png') == false) {
            rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
            return null;
         }
      $file_info = getimagesize($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      $wm_info = getimagesize($this->mmhclass->info->root_path.'css/watermark/watermark.png');
      
         if($file_info[0] < $wm_info[0] || $file_info[1] < $wm_info[1]) {
            rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
            return null;
         }
      
      switch($this->mmhclass->info->config['watermark_position']) {
         case 'top_left':
         $X_pos = '0';
         $Y_pos = '0';         
         break;
         case 'top_right':
         $X_pos = $file_info[0] - $wm_info[0];
         $Y_pos = '0';
         break;
         case 'bottom_left':
         $X_pos = '0';
         $Y_pos = $file_info[1] - $wm_info[1];
         break;
         case 'bottom_right':
         $X_pos = $file_info[0] - $wm_info[0];
         $Y_pos = $file_info[1] - $wm_info[1];
         break;
         default:
         $X_pos = '0';
         $Y_pos = '0';
         break;
         }
      $watermark_img = imagecreatefrompng($this->mmhclass->info->root_path.'css/watermark/watermark.png');
      $wm_template = imagecreatetruecolor($file_info[0] , $file_info[1]);
      
      switch($file_info[2]) {
      case "1":
      $src_image = imagecreatefromgif($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      imagecopy($wm_template, $src_image, 0, 0, 0, 0, $file_info[0] , $file_info[1]);
      imagecopy($wm_template, $watermark_img, $X_pos, $Y_pos, 0, 0, $wm_info[0], $wm_info[1]);
      if (imagegif ($wm_template, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename) == true) {
         unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      }
      imagedestroy($wm_template);
      break;
      case "2":
      $src_image = imagecreatefromjpeg($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      imagecopy($wm_template, $src_image, 0, 0, 0, 0, $file_info[0] , $file_info[1]);
      imagecopy($wm_template, $watermark_img, $X_pos, $Y_pos, 0, 0, $wm_info[0], $wm_info[1]);
      if (imagejpeg ($wm_template, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename, 85) == true){
         unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      }
      imagedestroy($wm_template);
      break;
      case "3":
      $src_image = imagecreatefrompng($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      imagecopy($wm_template, $src_image, 0, 0, 0, 0, $file_info[0] , $file_info[1]);
      imagecopy($wm_template, $watermark_img, $X_pos, $Y_pos, 0, 0, $wm_info[0], $wm_info[1]);
      if (imagepng ($wm_template, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename, 9) == true) {
         unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename);
      }
      imagedestroy($wm_template);
      break;
      default:
      rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'wm_'.$filename, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$filename);
      break;
      }
      
      }
   // END WATERMARK MOD
		
		
		function rotateImage($image, $rotate, $imgtype, $rnd_dir) {
			switch($imgtype) {
			 case 'jpg': case 'jpeg':
				$source = imagecreatefromjpeg($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				$rot_img = imagerotate($source, $rotate, 0);
				if(imagejpeg($rot_img, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image, 80) == true) {
					 unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					 rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
					 imagedestroy($source);
					 return true;
				 }else { imagedestroy($source); return false;}
				break;
			  case 'png':
				$source = imagecreatefrompng($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				$rot_img = imagerotate($source, $rotate, 0);
				if(imagepng($rot_img, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image) == true) {
					unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
					  imagedestroy($source);
					 return true;
				}else { imagedestroy($source); return false;}
				break;
			case 'gif':
				$source = imagecreatefromgif($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				$rot_img = imagerotate($source, $rotate, 0);
				if(imagegif($rot_img, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image) == true) {
					unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'rot_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
					  imagedestroy($source);
					 return true;
				}else { imagedestroy($source); return false;}
				break;
		 	}
		}
		
		function grayscaleImage($image, $src_w, $src_h, $imgtype, $rnd_dir, $sepia = false) {
			switch($imgtype) {
			
				case 'jpg': case 'jpeg':
					$source = imagecreatefromjpeg($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					$dest = imagecreatefromjpeg($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					
					if (imagecopymergegray($dest, $source, 0, 0, 0, 0, $src_w, $src_h, 0) == true) {
						if ($sepia == true) {
							imagefilter($dest, IMG_FILTER_COLORIZE, 100, 50, 0);
						}if(imagejpeg($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image, 80) == true) {
							unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
							imagedestroy($source);
							imagedestroy($dest);
							return true;
						}
					}else {imagedestroy($source); imagedestroy($dest); return false;}
					break;
				case 'png':
					$source = imagecreatefrompng($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					$dest = imagecreatefrompng($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					
					if (imagecopymergegray($dest, $source, 0, 0, 0, 0, $src_w, $src_h, 0) == true) {
					if ($sepia == true) {
							imagefilter($dest, IMG_FILTER_COLORIZE, 100, 50, 0);
						}if(imagepng($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image) == true) {
							unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
							imagedestroy($source);
							imagedestroy($dest);
							return true;
						}
					}else {imagedestroy($source); imagedestroy($dest); return false;}
					break;
				case 'gif':
					$source = imagecreatefromgif($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					$dest = imagecreatefromgif($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
					
					if (imagecopymergegray($dest, $source, 0, 0, 0, 0, $src_w, $src_h, 0) == true) {
						if ($sepia == true) {
							imagefilter($dest, IMG_FILTER_COLORIZE, 100, 50, 0);
						}if(imagegif($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image) == true) {
							unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'gs_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
							 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
							imagedestroy($source);
							imagedestroy($dest);
							return true;
						}
					}else {imagedestroy($source); imagedestroy($dest); return false;}
					break;
			}
		}
		
		
		function image_flip($image, $type, $rnd_dir, $width, $height, $imgtype){
		$dest = imagecreatetruecolor($width, $height);
		switch($imgtype){
			case 'jpg': case 'jpeg':
				$img = imagecreatefromjpeg($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
			break;
			case 'png':
					$img = imagecreatefrompng($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
			break;
			case 'gif':
					$img = imagecreatefromgif($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
			break;
		}
		switch($type){
		case 'vert':
			for($i=0;$i<$height;$i++){imagecopy($dest, $img, 0, ($height - $i - 1), 0, $i, $width, 1);}
		break;
		case 'horiz':
			for($i=0;$i<$width;$i++){imagecopy($dest, $img, ($width - $i - 1), 0, $i, 0, 1, $height);}
		break;
		case 'both':
			for($i=0;$i<$width;$i++){imagecopy($dest, $img, ($width - $i - 1), 0, $i, 0, 1, $height);}
			$buffer = imagecreatetruecolor($width, 1);
			for($i=0;$i<($height/2);$i++){
				imagecopy($buffer, $dest, 0, 0, 0, ($height - $i -1), $width, 1);
				imagecopy($dest, $dest, 0, ($height - $i - 1), 0, $i, $width, 1);
				imagecopy($dest, $buffer, 0, $i, 0, 0, $width, 1);
			}
			imagedestroy($buffer);
			break;
		}
		switch($imgtype){
		case 'jpg': case 'jpeg':
			if(imagejpeg($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image, 80) == true) {
				unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				$this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
				imagedestroy($dest);
				return true;
			}else{ return false;}
		break;
		case 'png':
			if(imagepng($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image) == true) {
				unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
				 imagedestroy($dest);
				 return true;
			}else {return false;}
		break;
		case 'gif':
			if(imagegif($dest, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image) == true) {
				unlink($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				rename($this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.'flp_'.$image, $this->mmhclass->info->root_path.$this->mmhclass->info->config['upload_path'].$rnd_dir.$image);
				 $this->mmhclass->image->create_thumbnail($image, true, 0, $rnd_dir);
				 imagedestroy($dest);
				 return true;
			}else {return false;}
		}
		}
}
	
?>