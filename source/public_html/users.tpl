<!-- BEGIN: USER REGISTRATION PAGE -->
<template id="registration_page">
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Register at <# SITE_NAME #></h2>
					</div>
					<div class="box-content">
<if="$mmhclass->info->config['seo_urls'] == 1">
<form action="register-d" method="post">
<else>
<form action="users.php?act=register-d" method="post">
</endif>
<input type="hidden" name="return" value="<# RETURN_URL #>" />
	<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
            <tr>
                <td style="width: 30%;" class="tdrow1"><span>Username:</span> <br /> <div class="explain">Please enter an username that is 3 to 30 characters in length and only contain the characters: <p class="help" title="-_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789">-_A-Za-z0-9</p>. The username that you pick cannot be changed later.</div></td> 
                <td class="tdrow2" valign="top">
                    <input type="text" name="username" id="username_field" class="input_field" style="width: 300px;" maxlength="30" />
                    <br /><br />
                    <div id="username_check"><a href="javascript:void(0);" onclick="check_username();">Check Availability</a></div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tdrow1"><span>Password:</span> <br /> <div class="explain">Please enter a password that is 6 to 30 characters in length. It is also recommended to randomize the password for enhanced security. <a href="http://www.whatsmyip.org/passwordgen/" target="_blank">Password Generator</a></div></td> 
                <td class="tdrow2" valign="top"><input type="password" name="password" class="input_field" style="width: 300px;" maxlength="30" /></td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tdrow1"><span>Password (retype):</span></td> 
                <td class="tdrow2"><input type="password" name="password-c" class="input_field" style="width: 300px;" /></td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tdrow1"><span>E-Mail Address:</span> <br /> <div class="explain">This is the E-Mail Address at which we will contact you when changes happen to our services that may affect your account. Please read our <a href="info.php?act=privacy_policy" target="_blank">Privacy Policy</a>.</div></td> 
                <td class="tdrow2" valign="top"><input type="text" class="input_field" name="email_address" style="width: 300px;" /></td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tdrow1" valign="top"><span>Security Code:</span></td> 
                <td class="tdrow2"><# CAPTCHA_CODE #></td>
            </tr>
            <tr>
                <td style="width: 30%;" class="tdrow1"><span>Do you agree?</span> <br /> <div class="explain">By clicking "Finish Registration" I understand the <a href="info.php?act=privacy_policy">Privacy Policy</a> and <a href="info.php?act=rules">Terms of Service</a>.</div>
                </td> 
                <td class="tdrow2" colspan="2" style="height: 35px;">
       	            	<input data-no-uniform="true" type="checkbox" class="iphone-toggle" name="iagree" id="iagree" value="1" />
                </td>
            </tr>
            <tr>
                <td colspan="2"><div class="center"><button type="submit" class="btn btn-small btn-primary">Finish Registration</button></div></td>
            </tr>
        </table>
</form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: USER REGISTRATION PAGE -->

<!-- BEGIN: USER REGISTRATION HARD LIMIT EMAIL -->
<template id="user_registration_hard_limit">
<br />
<# SITE_NAME #> Administrator,
<br /><br />
The hard limit of 5 user accounts per IP address has<br />
been exceeded by the user with the IP address: <a href="http://whois.domaintools.com/<# IP_ADDRESS #>" target="_new"><# IP_ADDRESS #></a>.
<br /><br />
To take action, log in to the Admin Control Panel at:
<br /><br />
<# BASE_URL #>admin.php

</template>
<!-- END: USER REGISTRATION HARD LIMIT EMAIL -->

<!-- BEGIN: USER LOGIN LIGHTBOX -->
<template id="login_lightbox">
			<div class="row-fluid lightbox-ch" align="center">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Log In</h2>
						<div class="box-icon">
              <a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
<if="$mmhclass->info->config['seo_urls'] == 1">
<form action="login-d" method="post">
<else>
<form action="users.php?act=login-d" method="post">
</endif>
	<input type="hidden" name="return" value="<# RETURN_URL #>" />
    
	<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td class="text_align_right" style="width: 45%;"><span>Username</span>:&nbsp;</td> 
            <td><input type="text" name="username" class="input_field" style="width: 200px;" /></td>
        </tr>
        <tr>
         	<td class="text_align_right" style="width: 45%;"><span>Password</span>:&nbsp;</td>
            <td><input type="password" name="password" class="input_field" style="width: 200px;" /></td>
        </tr>
        <tr>
            <td class="text_align_center" style="font-size: 10px;" colspan="2">
                ( <a href="javascript:void(0);" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>'); toggle_lightbox('users.php?act=lost_password', 'lost_password_lightbox');">Reset Password</a> |
		<if="$mmhclass->info->config['seo_urls'] == 1">
			<a href="register">Register</a> )
		<else>
                	<a href="users.php?act=register&amp;return=<# RETURN_URL #>">Register</a> )
</endif>
            </td>
        </tr>
        <tr>
            <td colspan="2"><div class="center"><button type="submit" class="btn btn-small btn-primary">Log In</button></div></td>
        </tr>
    </table>
</form>
<div class="center">
<a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');"><button class="btn btn-small btn-primary">Close Window</button></a>
</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: USER LOGIN LIGHTBOX -->

<!-- BEGIN: FORGOTTEN PASSWORD LIGHTBOX -->
<template id="forgotten_password_lightbox">
			<div class="row-fluid lightbox-ch" align="center">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Reset My Password</h2>
					</div>
					<div class="box-content">
<form action="users.php?act=lost_password-d" method="post">
	<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="text_align_right" style="width: 45%;"><span>Username</span>:&nbsp;</td> 
			<td><input type="text" name="username" class="input_field" style="width: 200px;" /></td>
		</tr>
		<tr>
			<td class="text_align_right" style="width: 45%;"><span>E-Mail Address</span>:&nbsp;</td> 
			<td><input type="text" name="email_address" class="input_field" style="width: 200px;" /></td>
		</tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
	</table>
<div align="center"><button type="submit" class="btn btn-small btn-primary">Send Password</button>
</div>
</form>
&nbsp;<a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');"><button class="btn btn-small btn-primary">Close Window</button></a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>


</template>
<!-- END: FORGOTTEN PASSWORD LIGHTBOX -->

<!-- BEGIN: FORGOTTEN PASSWORD EMAIL -->
<template id="forgotten_password_email">
<br />
Hello <# USERNAME #>,
<br /><br />
You are receiving this email because you have (or someone pretending to be you has) requested<br />
a new password be set for your account on <a href="<# BASE_URL #>"><# SITE_NAME #></a>. If you did not request this email,<br />
then please ignore it, and if you keep receiving it, then please <a href="<# BASE_URL #>contact.php?act=contact_us">contact the site administrator</a>.
<br /><br />
To use the new password you need to activate it. To do this click the link provided below.
<br /><br />
<a href="<# BASE_URL #>users.php?act=lost_password-a&id=<# AUTH_KEY #>">Activate New Password</a>
<br /><br />
If successful you will be able to log in using the following information:
<br /><br />
<strong>Username:</strong> <# USERNAME #><br />
<strong>Password:</strong> <# NEW_PASSWORD #>
<br /><br />
<em>Please keep in mind that the password you enter is case sensitive.</em>
<br /><br />
----<br />
<# SITE_NAME #> Support<br />
<# ADMIN_EMAIL #>

</template>
<!-- END: FORGOTTEN PASSWORD EMAIL -->

<!-- BEGIN: USER LIST PAGE -->
<template id="user_list_page">

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Member Galleries</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable" id="members">
						  <thead>
							  <tr>
								  <th>Username</th>
								  <th>Date registered</th>
								  <th>Gallery Status</th>
								  <th>Total Uploads</th>
								  <th></th>
							  </tr>
						  </thead>   
						  <tbody>

		<while id="user_list_whileloop">
		<if="$mmhclass->info->config['seo_urls'] == 1">
			<tr>
				<td width="15%"><# USERNAME #></td>
				<td class="center" width="20%"><# TIME_JOINED #></td>
				<td class="center" width="10%"><# GALLERY_STATUS #></td>
				<td class="center" width="10%"><# TOTAL_UPLOADS #></td>
				<td class="center" width="15%">
									<a class="btn btn-success" href="#">
										<i class="icon-user icon-white"></i>  
										Profile
									</a>
									<a class="btn btn-info" href="#">
										<i class="icon-edit icon-white"></i>  
										Gallery
									</a>
				</td>
			</tr>
		<else>
			<tr>
				<td width="15%"><strong><# USERNAME #></strong></td>
				<td class="center" width="20%"><# TIME_JOINED #></td>
				<td class="center" width="10%"><# GALLERY_STATUS #></td>
				<td class="center" width="10%"><# TOTAL_UPLOADS #></td>
				<td class="center" width="15%">
									<a class="btn btn-success" href="profile.php?id=<# USER_ID #>">
										<i class="icon-user icon-white"></i>  
										Profile
									</a>
									<a class="btn btn-info" href="users.php?act=gallery&amp;gal=<# USER_ID #>">
										<i class="icon-edit icon-white"></i>  
										Gallery
									</a>
				</td>
			</tr>
		</endif>
		</endwhile>

						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

</template>
<!-- END: USER LIST PAGE -->

<!-- BEGIN: MY GALLERY PAGE -->
<template id="my_gallery_page">
<div class="align_left_mfix">
    <ul class="jd_menu">
        <if="$mmhclass->info->user_owned_gallery == true">
     		<li><span onclick="gallery_action('delete');" title="Delete Selected" class="button1">Delete Images</span></li>
            <li><span onclick="gallery_action('move');" title="Move Selected" class="button1">Move Images</span></li>
      		<li><span onclick="gallery_action('select');" title="Select/Deselect All" class="button1">Select All</span></li>
		<li><span onclick="gallery_action('make_ext_gal');" title="Create external Gallery" class="button1">Make Gallery</span></li>
        </endif>
        
      	<li><span class="button1">Album List</span>
            <ul class="menu_border">
                <if="$mmhclass->info->user_owned_gallery == true">
                    <li class="header">Actions</li>
                    <li class="item"><a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-c', 'new_album_lightbox');">New Album</a></li>
                </endif>
                
                <li class="header">Albums</li>
		<if="$mmhclass->info->config['seo_urls'] == 1">
		  <li class="item"><a href="<# USER_NAME #>">Root Album</a> (<# TOTAL_ROOT_UPLOADS #> of <# TOTAL_UPLOADS #> images)</li>
		<else>
                <li class="item"><a href="<# GALLERY_URL #>">Root Album</a> (<# TOTAL_ROOT_UPLOADS #> of <# TOTAL_UPLOADS #> images)</li>
                   </endif>
                <while id="album_pulldown_whileloop">
                    <li class="item"> 
			<if="$mmhclass->info->config['seo_urls'] == 1">
			 <strong>&bull;</strong> <a href="<# USER_NAME #><# ALBUM_NAME #>"><# ALBUM_NAME #></a> (<# TOTAL_UPLOADS #> images) 
			<else>
                        <strong>&bull;</strong> <a href="<# GALLERY_URL #>&amp;cat=<# ALBUM_ID #>"><# ALBUM_NAME #></a> (<# TOTAL_UPLOADS #> images) 
                       </endif>
                        <if="$mmhclass->info->user_owned_gallery == true">
                            ( <a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-d&amp;album=<# ALBUM_ID #>', 'delete_album_lightbox');">Delete</a> |
                            <a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-r&amp;album=<# ALBUM_ID #>', 'rename_album_lightbox');">Edit</a> )
                        </endif>
                    </li>
                </endwhile>
      		</ul>
      	</li>
        
        <if="$mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true">
            <li><span class="button1">Search</span>
                <ul class="menu_border">
                    <li class="header">Search this Album</li>
                    <li class="item text_align_center">
                        <input type="text" id="file_search" class="input_field" maxlength="25" style="width: 130px;" value="Enter Filename or Title" onclick="$(this).val('');" onkeydown="if(event.keyCode==13){$('input[id=file_search_button]').click();}" />
                        <input type="button" value="Go" id="file_search_button" onclick="location.href=('<# FULL_GALLERY_URL #>&amp;search=' + encodeURIComponent($('input[id=file_search]').val()));" />
                        <br /><br />
                        <b>%</b> and <b>_</b> are <a href="http://dev.mysql.com/doc/refman/5.0/en/string-comparison-functions.html#operator_like" target="_blank">wildcard characters</a>.
                    </li>
                </ul>
            </li>
        <else>
        	<li><a href="<# FULL_GALLERY_URL #>" class="button1">Clear Search</a></li>
        </endif>
    </ul>
</div>

<# PAGINATION_LINKS #>
<br /><br />

			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> 
            		<if="$mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true">
               			<# GALLERY_OWNER #>'s Gallery <# ALBUM_NAME #>
      				<else>
                    	Searching for "<# IMAGE_SEARCH #>"
                	</endif>
						</h2>
					</div>
					<div class="box-content">

<if="$mmhclass->templ->templ_globals['empty_gallery'] == true">
	<# EMPTY_GALLERY #>
<else>
        <table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
            <tr>
	            <th colspan="4">
                </th>
            </tr>
            <tr>
                <# GALLERY_HTML #>
            </tr>
        </table>
    
    <div class="pagination_footer">
        <# PAGINATION_LINKS #>
    </div>
</endif>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: MY GALLERY PAGE -->

<!-- BEGIN: MOVE FILES LIGHTBOX -->
<template id="move_files_lightbox">
			<div class="row-fluid lightbox-ch" align="center">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Move Images</h2>
					</div>
					<div class="box-content">
<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="users.php?act=move_files-d" method="post">
				<p>
					<b>Move To</b>:
					<br /><br />
                    
					<select name="move_to" style="width: 200px;">
						<option value="root">Root Album</option>
                        
						<while id="album_options_whileloop">
							<option value="<# ALBUM_ID #>">&bull; <# ALBUM_NAME #></option>
						</endwhile>
					</select>
					<br /><br />
                    
					<input type="hidden" value="<# FILES2MOVE #>" name="files" />
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
				</p>
		</td>
	</tr>
        <tr>
            <td colspan="2"><div class="center"><button type="submit" class="btn btn-small btn-primary">Move Images</button></div></td>
        </tr>
			</form>
</table>
<div class="center">
<a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');"><button class="btn btn-small btn-primary">Close Window</button></a>
</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: MOVE FILES LIGHTBOX -->

<!-- BEGIN: DELETE FILES LIGHTBOX -->
<template id="delete_files_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Confirm Image Deletion</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="users.php?act=delete_files-d" method="post">
				<p>
					Are you sure you wish to carry out this operation? 
					<br /><br />
					If you select "Yes" there is no undo.
					<br /><br />
                    
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
					<input type="hidden" value="<# FILES2DELETE #>" name="files" />
                    
					<input type="submit" value="Yes" class="button1" />
					<input type="button" value="No" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
				</p>
			</form>
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

</template>
<!-- END: DELETE FILES LIGHTBOX -->

<!-- BEGIN: NEW ALBUM LIGHTBOX -->
<template id="new_album_lightbox">
 <script type="text/javascript">
 function pwtoggle() {
 
	if ($('input[name=enablepw]').is(':checked')  == true) {
		$('input[name=album_password]').removeAttr('disabled');
	}else {$('input[name=album_password]').attr('disabled',true);}
}
  </script>
<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>New Album</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="users.php?act=albums-c-d" method="post">
				<p>
					<b>Album Title</b>:
					<br /><br />
                    
					<input type="text" name="album_title" maxlength="50" class="input_field" style="width: 250px;" />
					<br /><br />
					<strong>Make album private?</strong><br/>
					<input type="radio" name="is_private" value="1" /> Yes <input type="radio" name="is_private" value="0" checked /> No
					<br /><br/>
					<strong>Password protect album?</strong><br/>
					<input type="checkbox" onchange="pwtoggle();" id="pwcheckbox" name="enablepw" /> Enable password protection<br/><strong>Password:</strong><br/> <input type="text" name="album_password" maxlength="80" class="input_field" style="width: 250px;" disabled id="pwinput" />
					
					<br/><br/>
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
                    
					<input type="submit" value="Create Album" class="button1" />
					<input type="button" value="Cancel" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
				</p>
			</form>
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

</template>
<!-- END: NEW ALBUM LIGHTBOX -->

<!-- BEGIN: RENAME ALBUM LIGHTBOX -->
<template id="rename_album_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Rename Album</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="users.php?act=albums-r-d" method="post">
				<p>
					<b>New Album Title</b>:
					<br /><br />
                    
					<input type="text" name="album_title" maxlength="50" class="input_field" style="width: 250px;" value="<# OLD_TITLE #>" onclick="$(this).val('');" />
					<br /><br />
					<strong>Private Album?</strong><br/>
					<input type="radio" name="is_private" value="1" <# IS_PRIVATE #> /> Yes <input type="radio" name="is_private" value="0" <# IS_NOT_PRIVATE #>  /> No
					<br /><br />
					<strong>Album password?</strong><br/>
					<input type="checkbox" id="pwcheckbox" name="enablepw" <# HAS_PASSWORD #> /> Uncheck to delete current password
					<br/><br />
					<strong>Password:</strong><br/> <input type="text" name="album_password" maxlength="80" class="input_field" style="width: 250px;" id="pwinput" />
					<br/>Leave blank to keep current password, if set.
					<br /><br />
					<input type="hidden" value="<# ALBUM_ID #>" name="album" />
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
					<input type="hidden" value="<# HAS_PASSWORD #>" name="state" />
					<input type="submit" value="Save changes" class="button1" />
					<input type="button" value="Cancel" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
				</p>
	    	</form>
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

</template>
<!-- END: RENAME ALBUM LIGHTBOX -->

<!-- BEGIN: DELETE ALBUM LIGHTBOX -->
<template id="delete_album_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Confirm Album Deletion</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="users.php?act=albums-d-d" method="post">
				<p>
					Are you sure you wish to carry out this operation? 
					<br /><br />
					If you select "Yes" there is no undo.
					<br /><br />
                    
					<input type="hidden" value="<# ALBUM2DELETE #>" name="album" />
                    
					<input type="submit" value="Yes" class="button1" />
					<input type="button" value="No" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
                    <br /><br />
                    
					<div style="font-size: 10px; font-style: italic;">
                    	<strong>Note:</strong> Images within this album will be moved to the root album, not deleted.
                    </span>
				</p>
			</form>
			<br /><br />
		</td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

</template>
<!-- END: DELETE ALBUM LIGHTBOX -->

<!-- BEGIN: USER SETTINGS PAGE -->
<template id="user_settings_page">
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> User Profile Settings</h2>
					</div>
					<div class="box-content">
<form action="users.php?act=settings-s" method="post">
						<table class="table table-striped table-bordered">
						  <thead>
							  <tr>
								  <th>User Profile Settings</th>
							  </tr>
						  </thead>   
						  <tbody>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>User ID</span>:</td>
				<td class="tdrow2"><# USER_ID #></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Username</span>:</td>
				<td class="tdrow2"><# USERNAME #></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;" valign="top"><span>Password</span>: <br /> <div class="explain">Please enter a password that is 6 to 30 characters in length. It is also recommended to randomize the password for enhanced security. To create a secure random password try the <a href="http://whatsmyip.org/passwordgen/" target="_blank">Password Generator</a>.</div></td>
				<td class="tdrow2" valign="top"><input type="password" style="width: 300px;" class="input_field" name="password" maxlength="30" value="*************" /></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>IP Address</span>:</td>
				<td class="tdrow2"><p title="<# IP_HOSTNAME #>" class="help"><# IP_ADDRESS #></p> ( <a href="http://whois.domaintools.com/<# IP_ADDRESS #>" target="_blank">Whois</a> | <a href="http://just-ping.com/index.php?vh=<# IP_ADDRESS #>" target="_blank">Ping</a> )</td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>E-Mail Address</span>: <br /> <div class="explain">This is the E-Mail Address at which we will contact you when changes happen to our services that may affect your account.</div></td>
				<td class="tdrow2" valign="top"><input type="text" style="width: 300px;" name="email_address" class="input_field" value="<# EMAIL_ADDRESS #>" /></td>
				<td class="tdrow2"><input type="radio" name="email_visible" value="0" <# EMAIL_NOONE #> />Private <input type="radio" name="email_visible" value="1" <# EMAIL_EVERYONE #> />Public <input type="radio" name="email_visible" value="2" <# EMAIL_FRIENDS #> />Friends</td>
			</tr>
			<tr>
				<td style="width: 38%"><span>Private Gallery</span>: <br /> <div class="explain">Enabling this option will make it so only you or a site administrator can view your entire gallery. If disabled, then during uploading you can choose to make the images being uploaded private or not.</div></td>
				<td class="tdrow2" valign="top"><input type="radio" name="private_gallery" value="1" <# PRIVATE_GALLERY_YES #> /> Yes <input type="radio" name="private_gallery" value="0" <# PRIVATE_GALLERY_NO #> /> No</td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Default Upload Layout</span>:</td>
				<td class="tdrow2"><input type="radio" name="upload_type" value="standard" <# STANDARD_UPLOAD_YES #> /> <p onclick="toggle_lightbox('index.php?layoutprev=std', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Standard</p> <input type="radio" name="upload_type" value="boxed" <# BOXED_UPLOAD_YES #> /> <p onclick="toggle_lightbox('index.php?layoutprev=bx', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Boxed</p></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Date Registered</span>:</td>
				<td class="tdrow2"><# TIME_JOINED #></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>User Group</span>:</td>
				<td class="tdrow2"><# USER_GROUP #></td>
				<td class="tdrow2"></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>My Country</span>:</td>
				<td class="tdrow2" valign="top"><input type="text" style="width: 300px;" name="from_country" class="input_field" value="<# FROM_COUNTRY #>" /></td>
				<td class="tdrow2"><input type="radio" name="country_visible" value="0" <# COUNTRY_NOONE #> />Private <input type="radio" name="country_visible" value="1" <# COUNTRY_EVERYONE #> />Public <input type="radio" name="country_visible" value="2" <# COUNTRY_FRIENDS #> />Friends</td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>My City</span>:</td>
				<td class="tdrow2" valign="top"><input type="text" style="width: 300px;" name="from_city" class="input_field" value="<# FROM_CITY #>" /></td>
				<td class="tdrow2"><input type="radio" name="city_visible" value="0" <# CITY_NOONE #> />Private <input type="radio" name="city_visible" value="1" <# CITY_EVERYONE #> />Public <input type="radio" name="city_visible" value="2" <# CITY_FRIENDS #> />Friends</td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Homepage URL</span>:</td>
				<td class="tdrow2" valign="top"><input type="text" style="width: 245px;" name="homepage_url" class="input_field" value="<# HOMEPAGE_URL #>" /></td>
				<td class="tdrow2">
				<input type="radio" name="homepage_visible" value="0" <# HOMEPAGE_NOONE #> />Private <input type="radio" name="homepage_visible" value="1" <# HOMEPAGE_EVERYONE #> />Public <input type="radio" name="homepage_visible" value="2" <# HOMEPAGE_FRIENDS #> />Friends</td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Facebook Profile</span>:</td>
				<td class="tdrow2" valign="top"><input type="text" style="width: 300px;" name="facebook_page" class="input_field" value="<# FACEBOOK_PAGE #>" /></td>
				<td class="tdrow2"><input type="radio" name="facebook_visible" value="0" <# FACEBOOK_NOONE #> />Private <input type="radio" name="facebook_visible" value="1" <# FACEBOOK_EVERYONE #> />Public <input type="radio" name="facebook_visible" value="2" <# FACEBOOK_FRIENDS #> />Friends</td>
			</tr>
						  </tbody>
					  </table>
<div align="center"><button type="submit" class="btn btn-small btn-primary">Save Settings</button></div>
</form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: USER SETTINGS PAGE -->
<!-- BEGIN: EXT GALLERY LIGHTBOX -->
<template id="ext_gallery_lightbox">
<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>External Gallery for Websites</th>
	</tr>
	<tr>
	<td class="tdrow1 text_align_center">
	<form action="users.php?act=do_ext_gal" method="post">
	<H3>Design</H3>
	<input type="radio" name="design" value="left">Left <input type="radio" name="design" value="right">Right <input type="radio" name="design" value="bottom" checked>Bottom
	<br/><br /><H3>Color</H3>
	<select name="color"> 
		<option value="grey" selected="selected">Grey</option>
		<option value="green">Green</option>
		<option value="blue">Blue</option>
		<option value="black">Black</option>
		<option value="red">Red</option>
		<option value="yellow">Yellow</option>
	</select>
	<br /><br />
	<input type="hidden" value="<# RETURN_URL #>" name="return" />
	<input type="hidden" value="<# FILES2GALERIZE #>" name="files" />
	<input type="submit" value="Create Gallery" class="button1" />
	<input type="button" value="Cancel" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
	</form>
	</td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>
</template>
<!-- END: EXT GALLERY LIGHTBOX -->

<!-- BEGIN: EXT GALLERY DONE -->
<template id="ext_gallery_done">
<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>External Gallery for Websites</th>
	</tr>
	<tr>
	<td><h3>Copy and paste this code</h3><br/>
	<textarea rows="35" cols="80" onclick="highlight(this);"><# GAL_CSS #><# EXT_GAL_LAYOUT #></textarea>
	<br />
	<br />
	<center>
	<a href="users.php?act=gallery">Return to Gallery</a><br />
	<a href="index.php">Site Index</a>
	</center>
	</td>
	</tr>
	<tr>
		<td class="table_footer"></td>
	</tr>
</table>
</template>
<!-- END: EXT GALLERY DONE -->

<!-- BEGIN: USER GALLERY CELL -->
<template id="user_gallery_layout">

<# TABLE_BREAK #>
<td class="<# TDCLASS #> text_align_center" valign="top">
<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->templ->templ_globals['file_options'] == true">
		<input type="checkbox" name="userfile" value="<# FILENAME #>" />
    	<input type="text" id="<# FILE_ID #>_title_rename" maxlength="25" style="width: 165px; display: none;" class="input_field" onblur="gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');" onkeydown="if(event.keyCode==13){gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');}" />
		<span class="arial" title="Click to change title" id="<# FILE_ID #>_title" onclick="gallery_action('rename', this.id);" class="font-weight: 700;"><# FILE_TITLE #></span>  - <a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	<else>
		<a href="viewer.php?file=<# FILENAME #>" title="<# FILENAME #>"><strong><# FILE_TITLE #></strong></a> - <a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	</endif>
    
    <br /><br />
	<a href="viewer.php?file=<# FILENAME #>"><img src="index.php?module=thumbnail&amp;file=<# FILENAME #>" alt="<# FILENAME #>" style="border: 1px solid #000000;padding:1px;" /></a>
	<br /><br />
	<a href="download.php?file=<# FILENAME #>"><img border="0" src="./css/images/dl_new.png" alt="Download"  valign="center" title="Download" class="button1" /></a> <a href="javascript:void(0);" onclick="toggle_lightbox('index.php?module=fileinfo&amp;file=<# FILENAME #>', 'file_info_lightbox');"><img border="0" src="./css/images/img_info.png" alt="More Info"  valign="center" title="More Info" class="button1" /></a><# EDITOR_LINK #>
</td>
<else>
	<if="$mmhclass->templ->templ_globals['file_options'] == true">
		<input type="checkbox" name="userfile" value="<# FILENAME #>" />
    	<input type="text" id="<# FILE_ID #>_title_rename" maxlength="25" style="width: 165px; display: none;" class="input_field" onblur="gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');" onkeydown="if(event.keyCode==13){gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');}" />
		<span class="arial" title="Click to change title" id="<# FILE_ID #>_title" onclick="gallery_action('rename', this.id);" class="font-weight: 700;"><# FILE_TITLE #></span>  - <a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	<else>
		<a href="<# FILENAME #>.html" title="<# FILENAME #>"><strong><# FILE_TITLE #></strong></a> - <a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	</endif>
    
    <br /><br />
	<a href="<# FILENAME #>.html"><img src="thumb.<# FILENAME #>" alt="<# FILENAME #>" style="border: 1px solid #000000;padding:1px;" /></a>
	<br /><br />
	<a href="download.php?file=<# FILENAME #>"><img border="0" src="./css/images/dl_new.png" alt="Download"  valign="center" title="Download" class="button1" /></a> <a href="javascript:void(0);" onclick="toggle_lightbox('index.php?module=fileinfo&amp;file=<# FILENAME #>', 'file_info_lightbox');"><img border="0" src="./css/images/img_info.png" alt="More Info"  valign="center" title="More Info" class="button1" /></a><# EDITOR_LINK #>
</td>
</endif>
</template>
<!-- END: USER GALLERY CELL -->

<!-- BEGIN: IMAGE EDITOR PAGE -->
<template id="img_editor_page">
	<script type="text/javascript" src="source/includes/scripts/editor/jQueryEasing.js"></script>
	<script type="text/javascript" src="source/includes/scripts/editor/jQueryRotate.js"></script>
	<script type="text/javascript" src="source/includes/scripts/editor/grayscale.js"></script>
	<script type="text/javascript" src="source/includes/scripts/editor/pixastic.js"></script>
	<script type="text/javascript" src="source/includes/scripts/editor/edit_functions.js"></script>
<div class="text_align_center">
<img src="css/images/counter_clockwise.png" alt="Rotate Image Counterclockwise" border="0" onclick="rotate(-90);"> <img src="css/images/180_dg.png" alt="Rotate Image 180DG" border="0" onclick="rotate(180);"> <img src="css/images/clockwise.png" alt="Rotate Image Counterclockwise" border="0" onclick="rotate(90);"> <img src="css/images/greyscale.png" alt="Render image greyscale" border="0" onclick="makeitgray();"> <img src="css/images/sepia.png" alt="Sepia Filter" border="0" onclick="doPixastic('sepia');"> <img src="css/images/flip_h.png" alt="Flip Horizontally" border="0" onclick="doPixastic('flipv');"><img src="css/images/flip_v.png" alt="Flip Vertically" border="0" onclick="doPixastic('fliph');"> <img src="css/images/save.png" alt="Save changes" border="0" onclick="saveImage('<# FILE_ID #>');"> <img src="css/images/undo.png" alt="Undo changes" border="0" onclick="undoChanges();"> <a href="users.php?act=gallery"><img src="css/images/return.png" alt="Return to Gallery" border="0"></a>
<br /><hr/>
<div id="response"></div>
<div id="imgeditor" style="overflow:scroll;">
	<img src="img.php?image=<# FILENAME #>" alt="Image to edit" style="border: 1px solid #000000; padding: 2px; max-width:840px;margin-top:1%;" id="imageineditor" />
	</div>
<br />
</template>
<!-- END: IMAGE EDITOR PAGE -->

<!-- BEGIN: ALBUM IS PW PROTECTED -->
<template id="album_has_pw_page">
<div class="text_align_center">
<h1>This Album is password protected!</h1>
<br/><br/>Please enter password:<br/>
<input type="text" name="password" class="input_field" style="width: 200px;" /></td><br/><br/>
<a onclick="checkPW(<# ALBUM_ID #>, <# GALLERY_ID #>);" class="button1">Submit</a>
<br/><br/><div id="result"></div>
</div>
</template>
<!-- END: ALBUM IS PW PROTECTED -->
