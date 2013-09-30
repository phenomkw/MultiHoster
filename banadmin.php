<?php
	// ======================================== \
	// Package: MultiHoster
	// Version: 6.0.0
	// Copyright (c) 2007-2013 Mihalism Technologies
	// Copyright (c) 2011-2013 MultiHosterScript.com
	// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
	// LTE: 1253515339 - Monday, September 21, 2009, 02:42:19 AM EDT -0400
	// ======================================== /
	
// include the files
include "./source/includes/config.php";
include "func.ban.php";
// switch statement to do pages in admin
switch ($_GET['x'])
    { 
       // if no page show bans
        default:
            listbans();
        break;
        // if add ban, show the form
        case "add":
            // if posted, insert it
            if ($_POST['add'])
                {
                    $ip = $_POST['ip'];
                    if (!$ip)
                        {
                            echo "You must put an ip address at least";
                        }
                    addban($ip,$_POST[reason],$_POST[legnth]);
                }
            // otherwise show form
            else
                {
                    echo "Add a ban.<br />";
                    echo "<form method='post' action='banadmin.php?x=add'>";
                    echo "IP Address<br /><input type='text' name='ip'><br />";    
                    echo "Reason<br /><input type='text' name='reason'><br />";    
                    echo "Long<br /><input type='text' name='long'><br />";
                    echo "<input type='submit' name='add' value='Add Ban'>";
                }
        break;
        // delete ban    
        case "delete":
            // got the id, preform the action
            if ($_GET['id'])
                {
                    delban($_GET['id']);
                }
            // show error
            else 
                {
                    echo "No ip selected to remove";
                }
        break;
    }
?>
