<?php
/* 
     // ======================================== \
     // Package: Mihalism Multi Host
     // Version: 5.0.0
     // Copyright (c) 2007, 2008, 2009 Mihalism Technologies
     // License: http://www.gnu.org/licenses/gpl.txt GNU Public License
     // LTE: 1252355785 - Monday, September 07, 2009, 04:36:25 PM EDT -0400
     // ======================================== /
     
     This file contains some language settings that are a part of Mihalism Multi Host but were not able to 
     be placed into template files. In each setting, %s represents a place holder for a value that will 
     be dynamically generated by Mihalism Multi Host; so be careful while editing to not remove them.
     
     Language file index:
		6897 -- Page title of website when installation is required
		5435 -- Message to be displayed when installation is required
		4648 -- Error to be displayed if an IP address is banned
		1188 -- Error to be displayed if an username is banned
		7414 -- Error to be displayed if no image manipulator is found
		9553 -- Error to be displayed if an incompatible PHP version is used
		2761 -- Message to be displayed for version checks

*/
    
	$mmhclass->lang['6897'] = "Installation Required";
	$mmhclass->lang['5435'] = "This copy of Mihalism Multi Host has yet to be installed.<br />
Please click <a href=\"install.php\">here</a> to continue to installation.";
	$mmhclass->lang['4648'] = "The IP address <b>%s</b> has been banned from use of %s.";
	$mmhclass->lang['1188'] = "The account owned by <b>%s</b> has been banned from use of %s.";
	$mmhclass->lang['7414'] = "The <a href=\"http://www.boutell.com/gd/\">GD Graphics Library</a> or <a href=\"http://pecl.php.net/package/imagick\">Imagick Image Library</a> was not found.";
	$mmhclass->lang['9553'] = "The PHP version of this server is not compatible with Mihalism Multi Host v%s. <br />
PHP 5.0.0 or above is required. It can be downloaded from <a href=\"http://php.net\">php.net</a>";
	$mmhclass->lang['2761'] = "%s is running: %s";


?>