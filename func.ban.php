<?php
// func.ban.php
// checks the ip to see if it is banned
function checkban($ip)
    {
        // querys database
        $q = mysql_query("SELECT * FROM `banned` WHERE `ip` = '$ip' LIMIT 1");
        $get = mysql_num_rows($q);
        // if found
        if ($get == "1")
            { 
                // deny user access
                $r=mysql_fetch_array($q);
                die("You have been banned from this website until $r[legnth]. If you feel this is in error, please contact the webmaster.");
            }
    }
// places a ban in the database
function addban($ip,$reason,$legnth)
    {
        // get current time
        $time = time();
        // inserts code into database
        $insert = mysql_query("INSERT INTO `banned` (`ip`,`time`,`long`,`reason`) VALUES ('$ip', '$time', '$long', '$reason')") or die("Could not add ban.<br />".mysql.error()."");
        echo "The ip address, $ip, has been added to the ban list.";
        echo "<br />Return to <a href='index.php'>Index</a>";
    }
// deletes a ban from the database
function delban($id)
    {
        // runs a delete query
        $delete = mysql_query("DELETE FROM `banned` WHERE `id` = '$id' LIMIT 1") or die("Could not remove ban.<br />".mysql.error()."");
        echo "The ip address has been removed from the ban list.";
        echo "Return to <a href='index.php'>Index</a>";
    }
// lists the bans in the ban admin
function listbans()
    { 
        // link to add ban
        echo "<a href='banadmin.php?x=add'>Add Ban</a><p>";
        echo "Return to <a href='index.php'>Index</a> <br/>";
        // loop to show all band
        $query = mysql_query("SELECT * FROM `banned` ORDER BY time DESC");
        $num = mysql_num_rows($query);
        if ($num)
            {
        while ($r=mysql_fetch_array($query))
            {
                echo "$r[ip] - $r[reason] - <a href='banadmin.php?x=delete&id=$r[id]'>Delete</a><br />";
            }
            }
    }
?>
