<!-- BEGIN: ACP STYLE #65468 -->
<template id="delete_album_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Confirm Album Deletion</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=albums-d-d" method="post">
				<p>
					Are you sure you wish to carry out this operation? 
					<br /><br />
					If you select "Yes" there is no undo.
					<br /><br />
                    
					<input type="hidden" value="<# USER_ID #>" name="user_id" />
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
<!-- END: ACP STYLE #65468 -->

<!-- BEGIN: ACP STYLE #94770 -->
<template id="rename_album_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Rename Album</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=albums-r-d" method="post">
				<p>
					<b>New Album Title</b>:
					<br /><br />
                    
					<input type="text" name="album_title" maxlength="50" class="input_field" style="width: 250px;" value="<# OLD_TITLE #>" onclick="$(this).val('');" />
					<br /><br />
                    
					<input type="hidden" value="<# ALBUM_ID #>" name="album" />
					<input type="hidden" value="<# USER_ID #>" name="user_id" />
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
                    
					<input type="submit" value="Rename Album" class="button1" />
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
<!-- END: ACP STYLE #94770 -->

<!-- BEGIN: ACP STYLE #68107 -->
<template id="new_album_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>New Album</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=albums-c-d" method="post">
				<p>
					<b>Album Title</b>:
					<br /><br />
                    
					<input type="text" name="album_title" maxlength="50" class="input_field" style="width: 250px;" />
					<br /><br />
                    
					<input type="hidden" value="<# USER_ID #>" name="user_id" />
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
<!-- END: ACP STYLE #68107 -->

<!-- BEGIN: ACP STYLE #27809 -->
<template id="move_files_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Move Images</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=move_files-d" method="post">
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
                    
					<input type="hidden" value="<# USER_ID #>" name="user_id" />
					<input type="hidden" value="<# FILES2MOVE #>" name="files" />
					<input type="hidden" value="<# RETURN_URL #>" name="return" />
                    
					<input type="submit" value="Move Images" class="button1" />
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
<!-- END: ACP STYLE #27809 -->

<!-- BEGIN: APC STYLE #74322 -->
<template id="admin_gallery_page">

<div class="align_left_mfix">
    <ul class="jd_menu">
    
        <if="$mmhclass->info->user_owned_gallery == false">
            <li><a href="modcp.php?act=user_list" class="button1">User Galleries</a>
     		<li><span onclick="gallery_action('delete');" title="Delete Selected" class="button1">Delete Images</span></li>
      		<li><span onclick="gallery_action('select');" title="Select/Deselect All" class="button1">Select All</span></li>
        <else>
     		<li><span onclick="gallery_action('delete');" title="Delete Selected" class="button1">Delete Images</span></li>
       	 	<li><span onclick="gallery_action('move', '<# GALLERY_ID #>');" title="Move Selected" class="button1">Move Images</span></li>
      		<li><span onclick="gallery_action('select');" title="Select/Deselect All" class="button1">Select All</span></li>
            
            <li><span class="button1">Album List</span>
                <ul class="menu_border">
                    <li class="header">Actions</li>
                    <li class="item"><a href="javascript:void(0);" onclick="toggle_lightbox('modcp.php?act=albums-c&amp;id=<# GALLERY_ID #>', 'new_album_lightbox');">New Album</a></li>
                    <li class="header">Albums</li>
                    <li class="item"><a href="<# GALLERY_URL #>">Root Album</a> (<# TOTAL_ROOT_UPLOADS #> of <# TOTAL_UPLOADS #> images)</li>
                  
                    <while id="album_pulldown_whileloop">
                        <li class="item"> 
                            <strong>&bull;</strong> <a href="<# GALLERY_URL #>&amp;cat=<# ALBUM_ID #>"><# ALBUM_NAME #></a> (<# TOTAL_UPLOADS #> images) 
                            ( <a href="javascript:void(0);" onclick="toggle_lightbox('modcp.php?act=albums-d&amp;album=<# ALBUM_ID #>&amp;id=<# GALLERY_ID #>', 'delete_album_lightbox');">Delete</a> |
                            <a href="javascript:void(0);" onclick="toggle_lightbox('modcp.php?act=albums-r&amp;album=<# ALBUM_ID #>&amp;id=<# GALLERY_ID #>', 'rename_album_lightbox');">Rename</a> )
                        </li>
                    </endwhile>
                </ul>
            </li>
    	</endif>
        
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

<if="$mmhclass->templ->templ_globals['empty_gallery'] == true">
	<# EMPTY_GALLERY #>
<else>
    <div class="table_border">
        <table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
            <tr>
	            <th colspan="4">
                
            		<if="$mmhclass->funcs->is_null($mmhclass->input->get_vars['search']) == true">
               			<# GALLERY_OWNER #>'s Gallery <# ALBUM_NAME #>
      				<else>
                    	Searching for "<# IMAGE_SEARCH #>"
                	</endif>
                    
                </th>
            </tr>
            <tr>
                <# GALLERY_HTML #>
            </tr>
            <tr>
                <td colspan="4" class="table_footer">&nbsp;</td>
            </tr>
        </table>
    </div>
    
    <div class="pagination_footer">
        <# PAGINATION_LINKS #>
    </div>
</endif>

</template>
<!-- END: ACP STYLE #74322 -->

<!-- BEGIN: ACP STYLE #71763 -->
<template id="delete_user_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Confirm Account Deletion</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=users-d-d" method="post">
				<p>
					Are you sure you wish to carry out this operation? 
					<br /><br />
					If you select "Yes" there is no undo.
					<br /><br />
                    
					<input type="hidden" value="<# USER2DELETE #>" name="id" />
                    
					<input type="submit" value="Yes" class="button1" />
					<input type="button" value="No" class="button1" onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');" />
                    <br /><br />
                    
					<div style="font-size: 10px; font-style: italic;">
                    	<strong>Note:</strong> Deleting an user will also delete any images that user has uploaded.
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
<!-- END: ACP STYLE #71763 -->

<!-- BEGIN: ACP STYLE #4842 -->
<template id="user_settings_page">

<form action="modcp.php?act=users-s-s" method="post">
	<input type="hidden" name="user_id" value="<# USER_ID #>" />
    
	<div class="table_border">
		<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
			<tr>
				<th colspan="2">User Settings</th>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>User ID</span>:</td>
				<td class="tdrow2"><# USER_ID #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Username</span>:</td>
				<td class="tdrow2"><# USERNAME #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;" valign="top"><span>Password</span>: <br /> <div class="explain">Please enter a password that is 6 to 30 characters in length. It is also recommended to randomize the password for enhanced security. To create a secure random password try the <a href="http://whatsmyip.org/passwordgen/" target="_blank">Password Generator</a>.</div></td>
				<td class="tdrow2" valign="top"><input type="password" style="width: 300px;" class="input_field" name="password" maxlength="30" value="*************" /></td>
			</tr>
			
			<tr>
				<td class="tdrow1" style="width: 38%;" valign="top"><span>E-Mail Address</span>:</td>
				<td class="tdrow2" valign="top">
               	 	<input type="text" style="width: 300px;" name="email_address" class="input_field" value="<# EMAIL_ADDRESS #>" /> 
                	<br />
                </td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%"><span>Private Gallery</span>:</td>
				<td class="tdrow2" valign="top"><input type="radio" name="private_gallery" value="1" <# PRIVATE_GALLERY_YES #> /> Yes <input type="radio" name="private_gallery" value="0" <# PRIVATE_GALLERY_NO #> /> No</td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Default Upload Layout</span>:</td>
				<td class="tdrow2"><input type="radio" name="upload_type" value="standard" <# STANDARD_UPLOAD_YES #> /> <p onclick="toggle_lightbox('index.php?layoutprev=std', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Standard</p> <input type="radio" name="upload_type" value="boxed" <# BOXED_UPLOAD_YES #> /> <p onclick="toggle_lightbox('index.php?layoutprev=bx', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Boxed</p></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Date Registered</span>:</td>
				<td class="tdrow2"><# TIME_JOINED #></td>
			</tr>
			
			<tr>
				<td class="table_footer" colspan="2"><input type="submit" value="Save Settings" class="button1" /></td>
			</tr>
		</table>
	</div>
</form> 

</template>
<!-- END: ACP STYLE #4842 -->

<!-- BEGIN: ACP STYLE #5981 -->
<template id="user_list_page">

<# PAGINATION_LINKS #>
<br /><br />

<div class="table_border">
	<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
		<tr>
			<th>#</th>
			<th>Username</th>
			<th>E-Mail Address</th>
			<th>Date Registered</th>
			<th>Gallery Status</th>
			<th>Total Uploads</th>
			<th>Actions</th>
		</tr>
        
		<while id="user_list_whileloop">
			<tr>				
				<td class="<# TDCLASS #>"><# USER_ID #></td>			
				<td class="<# TDCLASS #>"><a href="modcp.php?gal=<# USER_ID #>"><# USERNAME #></a></td>
				<td class="<# TDCLASS #>"><a href="modcp.php?act=mass_email&amp;id=<# USER_ID #>"><# EMAIL_ADDRESS #></a></td>
				<td class="<# TDCLASS #>"><# TIME_JOINED #></td>
				<td class="<# TDCLASS #>"><# GALLERY_STATUS #></td>
				<td class="<# TDCLASS #>"><# TOTAL_UPLOADS #></td>
				<td class="<# TDCLASS #>"><a href="modcp.php?act=users-s&amp;id=<# USER_ID #>">Settings</a></td>
			</tr>
		</endwhile>
        
		<tr>
			<td colspan="8" class="table_footer">&nbsp;</td>
		</tr>
	</table>
</div>

</template>
<!-- END: ACP STYLE #5981 -->

<!-- BEGIN: ACP STYLE #90107 -->
<template id="ban_control_page">

<script type="text/javascript">
	function validate_ip(info)
	{
		if (info.checked == true) {
			var current_ip = $("input[name=do_ban[ip_address]]").val();
			
			if (current_ip.match(/([\*])/g) !== null) {
				info.checked = false;
				alert("This function is not available in wildcard matches.");
			}	
		}
	}
</script>

<form action="modcp.php?act=ban_control-u" method="post">
	<div class="table_border">
		<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
			<tr>
				<th colspan="2">Manage Banned Users</th>
			</tr>
			<tr>
				<td style="width: 30%;" class="tdrow1" valign="top"><span>Ban Username:</span></td>
				<td class="tdrow2">
                	<input type="text" name="do_ban[username]" class="input_field" style="width: 300px;" />
                    <br /><br />
                    
                    <input type="checkbox" name="delete_files[username]" value="1" /> Delete <b>ALL</b> images uploaded by this user?
                </td>
			</tr>
			<tr>
				<td style="width: 30%;" class="tdrow1" valign="top"><span>Unban Username:</span></td> 
				<td class="tdrow2" valign="top">
					<select multiple="multiple" name="unban[username][]" style="width: 300px; height: 150px;" class="input_field">
                    
                        <while id="banned_user_whileloop">
                            <option value="<# BAN_ID #>" title="<# USERNAME #>"><# USERNAME #></option>
                        </endwhile>
				
                	</select>
				</td>
			</tr>
			<tr>
				<th colspan="2">Manage Banned IP Addresses</th>
			</tr>
			<tr>
				<td style="width: 30%;" class="tdrow1" valign="top"><span>Ban IP Address:</span> <br /> <div class="explain">Wildcard matching in the last two sections of an IP address is supported using the <b>*</b> character.<br /><br />Examples: 123.456.*.*, 123.456.*.789, or 123.456.789.*</td> 
				<td class="tdrow2" valign="top">
                	<input type="text" name="do_ban[ip_address]" class="input_field" style="width: 300px;" />
                    <br /><br />
                    
                    <input type="checkbox" name="delete_files[ip_address]" value="1" onclick="validate_ip(this);" /> Delete <b>ALL</b> images uploaded by this IP address?<br />
                    <input type="checkbox" name="delete_users[ip_address]" value="1" onclick="validate_ip(this);" /> Delete <b>ALL</b> user accounts created under this IP address?
                </td>
			</tr>
			<tr>
				<td style="width: 30%;" class="tdrow1" valign="top"><span>Unban IP Address: </span></td> 
				<td class="tdrow2" valign="top">
					<select multiple="multiple" name="unban[ip_address][]" style="width: 300px; height: 150px;" class="input_field">
                       
                        <while id="banned_ip_address_whileloop">
                            <option value="<# BAN_ID #>" title="<# IP_ADDRESS #>"><# IP_ADDRESS #></option>
                        </endwhile>
					
                    </select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="table_footer"><input type="submit" value="Update Ban Filter" class="button1" /></td>
			</tr>
		</table>
	</div>
</form>

</template>
<!-- END: ACP STYLE #90107 -->

<!-- BEGIN: ACP STYLE #34683 -->
<template id="delete_files_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Confirm Image Deletion</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
			<br />
			<form action="modcp.php?act=delete_files-d" method="post">
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
<!-- BEGIN: ACP STYLE #34683 -->