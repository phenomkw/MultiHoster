<?php
/* 
     // ======================================== \
     // Package: Mihalism Multi Host
     // Version: 5.0.0
     // Copyright (c) 2007, 2008, 2009 Mihalism Technologies
     // License: http://www.gnu.org/licenses/gpl.txt GNU Public License
     // LTE: 1248979979 - Thursday, July 30, 2009, 01:52:59 PM CDT -0500
     // ======================================== /
     
     This file contains some language settings that are a part of Mihalism Multi Host but were not able to 
     be placed into template files. In each setting, %s represents a place holder for a value that will 
     be dynamically generated by Mihalism Multi Host; so be careful while editing to not remove them.
     
     Language file index:
		3103 -- Full filesize names - Normal
		4191 -- Full filesize names - Plural
		7071 -- Full filesize names - Abbreviated
		5454 -- Notice to be displayed if a filesize cannot be calculated

*/
    
	$mmhclass->lang['3103'] = array (
  0 => 'Byte',
  1 => 'Kilobyte',
  2 => 'Megabyte',
  3 => 'Gigabyte',
  4 => 'Terabyte',
  5 => 'Petabyte',
  6 => 'Exabyte',
  7 => 'Zettabyte',
  8 => 'Yottabyte',
);
	$mmhclass->lang['4191'] = array (
  0 => 'Bytes',
  1 => 'Kilobytes',
  2 => 'Megabytes',
  3 => 'Gigabytes',
  4 => 'Terabytes',
  5 => 'Petabytes',
  6 => 'Exabytes',
  7 => 'Zettabytes',
  8 => 'Yottabytes',
);
	$mmhclass->lang['7071'] = array (
  0 => 'B',
  1 => 'KB',
  2 => 'MB',
  3 => 'GB',
  4 => 'TB',
  5 => 'PB',
  6 => 'EB',
  7 => 'ZB',
  8 => 'YB',
);
	$mmhclass->lang['5454'] = "Unknown Filesize";


?>