<!-- BEGIN: GLOBAL GALLERY CELL -->
<template id="global_gallery_layout">

<# TABLE_BREAK #>
<td class="center" style="text-align: center;">
<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->templ->templ_globals['file_options'] == true">
		<input type="checkbox" name="userfile" value="<# FILENAME #>" />
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
</template>
<!-- END: GLOBAL GALLERY CELL -->

<!-- BEGIN: GLOBAL MESSAGE BOX -->
<template id="global_message_box">
<br />
<div class="alert alert-info" align="center">
	<# MESSAGE #>
</div>

</template>
<!-- END: GLOBAL MESSAGE BOX -->

<!-- BEGIN: GLOBAL WARNING BOX -->
<template id="global_warning_box">
<br />
<div class="alert alert-error" align="center">
	<h1>General Notice</h1><br />
	<# ERROR #>
</div>

</template>
<!-- END: GLOBAL WARNING BOX -->

<!-- BEGIN: GLOBAL LIGHTBOX WARNING BOX -->
<template id="global_lightbox_warning">

			<div class="row-fluid lightbox-ch" align="center">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> General Notice</h2>
					</div>
					<div class="box-content">
          	<# ERROR #>
<br /><br />
<a class="btn btn-small btn-primary" onclick="$('div[class=lightbox_main]').parent().remove();">Close Window</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: GLOBAL LIGHTBOX WARNING BOX -->