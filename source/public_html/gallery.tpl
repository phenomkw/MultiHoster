<!-- BEGIN: PUBLIC GALLERY PAGE -->
<if="$mmhclass->templ->templ_globals['empty_gallery'] == true">
	<# EMPTY_GALLERY #>
<else>
<# PAGINATION_LINKS #>
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-picture"></i> Public Gallery</h2>
					</div>
					<div class="box-content">
<if="$mmhclass->info->is_admin == true">
	<script type="text/javascript" src="source/public_html/admin/adminjscript.js"></script>
	</endif>
<span class="align_left_mfix">
    <if="$mmhclass->input->get_vars['act'] == 'show_rated'">  
        <a href="gallery.php" class="button1">Order by Most Recent</a>
    <else>
    	<a href="gallery.php?act=show_rated" class="button1">Order by Rating</a>
    </endif>
   <if="$mmhclass->info->is_admin == true">
    <span onclick="gallery_action('delete');" title="Delete Selected" class="button1">Delete Images</span>
    <span onclick="gallery_action('select');" title="Select/Deselect All" class="button1">Select All</span>
     </endif>
</span>
<br /><br />
						<table class="table table-striped table-bordered bootstrap-datatable datatable" id="publicgallery">
						  <thead>
							  <tr>
                  <td colspan="4">The images that are presented below are images that have been uploaded by guest users. For a list of member galleries, click <a href="users.php?act=user_list">here</a>.</td>
							  </tr>
						  </thead>   
						  <tbody>
							<tr>
<while id="image_list_whileloop">
<# TABLE_BREAK #>
<td class="center" style="text-align: center;">
<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->templ->templ_globals['file_options'] == true">
		<label class="checkbox inline">
		<input type="checkbox" name="userfile" value="<# FILENAME #>" />
		</label>
    	<input type="text" id="<# FILE_ID #>_title_rename" maxlength="25" style="width: 165px; display: none;" class="input_field" onblur="gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');" onkeydown="if(event.keyCode==13){gallery_action('rename-d', '<# FILENAME #>', '<# FILE_ID #>');}" />
		<span class="arial" title="Click to change title" id="<# FILE_ID #>_title" onclick="gallery_action('rename', this.id);" class="font-weight: 700;"><# FILE_TITLE #></span>  - <a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	<else>
		<a href="viewer.php?file=<# FILENAME #>" title="<# FILENAME #>"><strong><# FILE_TITLE #></strong></a> - <a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><img src="css/images/mag_glass.png" border="0" alt="View Fullsize"></a>
	</endif>
    
    <br /><br />
	<a href="viewer.php?file=<# FILENAME #>"><img src="index.php?module=thumbnail&amp;file=<# FILENAME #>" alt="<# FILENAME #>" style="border: 1px solid #000000;padding:1px;" id="<# FILENAME #>"/></a>
	<br /><br />
	<a href="download.php?file=<# FILENAME #>"><img border="0" src="./css/images/download.png" alt="Download"  valign="center" title="Download" class="button1" /></a> <a href="javascript:void(0);" onclick="toggle_lightbox('index.php?module=fileinfo&amp;file=<# FILENAME #>', 'file_info_lightbox');"><img border="0" src="./css/images/info.png" alt="More Info"  valign="center" title="More Info" class="button1" /></a>
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
	<a href="<# FILENAME #>.html"><img src="thumb.<# FILENAME #>" alt="<# FILENAME #>" style="border: 1px solid #000000;padding:1px;" id="<# FILENAME #>"/></a>
	<br /><br />
	<a href="download.php?file=<# FILENAME #>"><img border="0" src="./css/images/download.png" alt="Download"  valign="center" title="Download" class="button1" /></a> <a href="javascript:void(0);" onclick="toggle_lightbox('index.php?module=fileinfo&amp;file=<# FILENAME #>', 'file_info_lightbox');"><img border="0" src="./css/images/info.png" alt="More Info"  valign="center" title="More Info" class="button1" /></a>
</td>
</endif>
</endwhile>
							</tr>
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

    <div class="pagination_footer">
        <# PAGINATION_LINKS #>
    </div>

</endif>
<!-- END: PUBLIC GALLERY PAGE -->