<!-- BEGIN: INSTALLER INTRO PAGE -->
<template id="installer_intro_page">

Welcome to Mihalism Multi Host! The Mihalism Multi Host mission is to provide the best image hosting software in the world. 
Our users know from experience that no hosting script on the Internet comes close to the power that Mihalism Multi Host can 
provide. Mihalism Technologies' development team knows that security and compatibility are the most important parts of any 
software. Therefore, we have developed advanced features that are compatible with almost any systems. So welcome to Mihalism 
Multi Host, we know you will love using it!
<br /><br />
You will need to have the following to allow Mihalism Multi Host to operate:
<br /><br />

<ol>
    <li><a href="http://httpd.apache.org/">Apache Web Server</a> or any <a href="http://www.google.com/search?q=httpd+server">standard</a> non-Windows web server.</li>
    <li><a href="http://www.mysql.com/">MySQL Database Server</a></li>
    <li><a href="http://www.php.net/">PHP: Hypertext Preprocessor</a> - <a href="http://www.php.net/downloads.php#v5">Version 5</a> or greater.</li>
    <li><a href="http://www.libgd.org/">GD Graphics Library</a> or <a href="http://pecl.php.net/package/imagick/">Imagick Image Library</a>*</li>
</ol><br />

<span style="margin-left: 30px;">
	<strong>Note:</strong> A requirement that is starred (*) is a requirements that is not common in shared hosting environments.
</span>
<br /><br />

<b style="color: #F00;">WARNING:</b> Using this installer will erase any already existing Mihalism Multi Host installation.
<br /><br />
Click the button shown below to start pre-installation checks.
<br /><br />

<a href="install.php?act=precheck"><img border="0" src="./css/images/install.jpg"/></a>
<hr style="margin-top: 6px;" />

<b>License</b>: Mihalism Multi Host is released under the GNU Version 3 General Public License. <br />
<span style="margin-left: 50px;">By using the script you agree to the terms and conditions set forth in the License. </span><br />
<span style="margin-left: 50px;">A copy of the license is packaged with the script in the file <a href="./help/LICENSE.pdf">LICENSE.pdf</a>.</span> 
<br /><br />

<b>Copyright:</b> Mihalism Multi Host is Copyright &copy; 2007, 2008, 2009 <a href="http://www.mihalism.net">Mihalism Technologies</a>.

</template>
<!-- END: INSTALLER INTRO PAGE -->

<!-- BEGIN: INSTALL PRECHECK PAGE -->
<template id="precheck">
<div class="table_border">
	<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
		<tr><th colspan="3">Pre-Install Checks Results:</th></tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>PHP Version:</span><br /><div class="explain">You need at leat PHP version 5 to run this script</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# PHP_VERSION_CHECK #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>PHP SafeMode:</span><br /></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# SAFE_MODE #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>GD Support:</span><br /><div class="explain">Without GD no Thumbnails and stuff!</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# PHP_GD_CHECK #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Imagick Support:</span><br /><div class="explain">Supports more stuff than GD.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# PHP_IMAGICK_CHECK #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /images/ is writable:</span><br /><div class="explain">If this failes you will not be able to upload any images!</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_IMAGES #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /source/errorlog/ is writable:</span><br /><div class="explain">If these folders are writeprotected errorlogging won't be possible and end in a PHP Fatal Error.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_ERRORLOG #></td>
		</tr>
				<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /source/errorlog/mysql is writable:</span><br /><div class="explain">If these folders are writeprotected errorlogging won't be possible and end in a PHP Fatal Error.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_ERRORLOG_MYSQL #></td>
		</tr>
			<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /source/errorlog/php5 is writable:</span><br /><div class="explain">If these folders are writeprotected errorlogging won't be possible and end in a PHP Fatal Error.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_ERRORLOG_PHP5 #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /source/tempfiles/ is writable:</span><br /><div class="explain">Although installation works with this folder not writable, it might be needed for future updates!</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_TEMPFILES #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /temp/ is writable:</span><br /><div class="explain">This folder needs to be writable in order for Plupload to work.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_IMGTEMP #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /temp/zip_uploads/ is writable:</span><br /><div class="explain">Needs to be writable for Zip Uploads</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_IMGTEMP_ZIP #></td>
		</tr>
		<tr>
			<td style="width: 40%;" class="tdrow1"><span>Check if folder /source/includes is writable:</span><br /><div class="explain">This folder needs to be writable just during installation so that config.php can be saved! You can remove the write permission after the installation is finished.</div></td>
			<td valign="top" style="width: 60%;" class="tdrow2"><# FOLDER_INCLUDES #></td>
		</tr>
		<tr>
			<td colspan="3" class="table_footer"><if="<# NUMBER_OF_ERRORS #> != 0"><b>Please correct the found errors and refresh this page!</b><else>
			<a href="install.php?act=install" class="button1">Start Installation</a></endif></td>
		</tr>
	</table>
</div>
</template>
<!-- END: INSTALL PRECHECK PAGE -->
<!-- BEGIN: INSTALL FORM PAGE -->
<template id="install_form_page">

Fill in the following form completely to install this version of Mihalism Multi Host. 
Once installed you can change these settings and others via the Admin Control Panel. 
<br /><br />

<form action="install.php?act=install-d" method="post">
	<div class="table_border">
		<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
			<tr>
				<th colspan="2">Mihalism Multi Host v<# MMH_VERSION #> Installer</th>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>MySQL Host:</span> <br /> <div class="explain">If you are unsure of your MySQL host, then please contact your hosting company before continuing. For most websites it will be "localhost"</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="text" name="sql_host" style="width: 300px;" value="localhost" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>MySQL Database Name:</span> <br /> <div class="explain">This will be the database in which all information related to your website will be archived. Mihalism Multi Host will not attempt to create one during the installation process so ensure that the database exists first.</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="text" name="sql_database" style="width: 300px;" value="<# MYSQL_USER #>_multihost" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>MySQL Username:</span> <br /> <div class="explain">The use of the "superadmin" or "root" user of a MySQL server is a very bad security practice so avoid use of it unless there is no other option available.</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="text" name="sql_username" style="width: 300px;" value="<# MYSQL_USER #>_multihost" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>MySQL Password (optional):</span> <br /> <div class="explain">It is not recommended to connect to MySQL without a password.</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="password" name="sql_password" style="width: 300px;" value="" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>Administrator Username:</span> <br /> <div class="explain">Please enter an username that is 3 to 30 characters in length and only contain the characters: <p class="help" title="-_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789">-_A-Za-z0-9</p>. Username cannot change later on.</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field" type="text" name="username" style="width: 300px;" value="Admin" maxlength="30" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>Administrator Password:</span> <br /> <div class="explain">Please enter a password that is 6 to 30 characters in length. It is also recommended to randomize the password for enhanced security.</div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="password" name="password" style="width: 300px;" value="" maxlength="30" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>Administrator Password (retype):</span></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="password" name="password-c" style="width: 300px;" value="" maxlength="30" /></td>
			</tr>
			<tr>
				<td style="width: 40%;" class="tdrow1"><span>Administrator E-Mail Address:</span> <br /> <div class="explain">By default, this is the E-Mail Address that Mihalism Multi Host addresses all incoming mail to. Once installed, the address that it sends mail to can be configured in the Admin Control Panel. Mihalism Multi Host has automatically set the E-Mail Address to send mail from to: <# EMAIL_OUT #></div></td>
				<td valign="top" style="width: 60%;" class="tdrow2"><input class="input_field"  type="text" name="email_address" style="width: 300px;" value="<# SERVER_ADMIN #>" /></td>
			</tr>
			<tr>
				<td colspan="2" class="table_footer"><input class="button1" type="submit" value="Finish Installation" /></td>
			</tr>
		</table>
	</div>
</form>

</template>
<!-- END: INSTALL FORM PAGE -->