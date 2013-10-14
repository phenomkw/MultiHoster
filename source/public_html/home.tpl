
<!-- BEGIN: NORMAL UPLOAD PAGE -->
<template id="normal_upload_page">
<if="$mmhclass->info->is_user == false">
<script type="text/javascript" src="source/includes/scripts/rc_comp.js"></script>
</endif>
<style type="text/css">@import url(source/plupload/queue/css/plupload.css);</style>
<script type="text/javascript" src="source/plupload/plupload.full.js"></script>
<script type="text/javascript" src="source/plupload/queue/plupload.js"></script>
<script type="text/javascript">

$(function() {
	$("#uploader").pluploadQueue({
		runtimes : 'html5,silverlight,flash,browserplus,html4',
		url : './upload.php',
		max_file_size : '<# MAX_UL_SIZE #>kb',
		chunk_size : '1mb',
		unique_names : false,
		max_file_count : 10,
		multipart : true,
		multipart_params: {session : '<# RAND_SES_VAR #>', upload_it : true},
		filters : [{title : "Image files", extensions : "<# ALLOWED_TYPES #>"}],
		flash_swf_url : 'source/plupload/plupload.flash.swf',
		silverlight_xap_url : 'source/plupload/plupload.silverlight.xap'
	});
	
	$('form').submit(function(e) {
	 var uploader = $('#uploader').pluploadQueue();
            if (uploader.files.length > 0) {
	    if (uploader.files.length > <# MAX_SIMUL_FILES #> ) {
		alert('Too many files in Queue!\nYou are only allowed to upload <# MAX_SIMUL_FILES #> file(s) at once!');
	}else {
            uploader.bind('StateChanged', function() {
		if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
			toggle_lightbox('index.php?act=upload_in_progress', 'progress_bar_lightbox'); 
                    $('form')[0].submit();
                }
            });
            uploader.start();
        }} else {

            alert('You must queue at least one file.');
        }
        return false;
    });
});

</script>
			<div class="row-fluid">
				<div class="box span13">
					<div class="box-header well">
						<h2><i class="icon-home"></i>
<strong>Welcome to <# SITE_NAME #>!</strong>
						</h2>
					</div>
					<div class="box-content">
<form name='image_uploads_form' id='upload_form' method='post' action='upload.php'>
<table width="100%">
	<tr>
		<td>
		<div align="center">
		<table border="0" width="100%">
	<tr>
		<td align="center">Max images per upload</td>
		<td align="center">Max filesize per image</td>
		<td align="center">Allowed File Extensions</td>
	</tr>
	<tr>
		<td align="center"><strong><# MAX_SIMUL_FILES #></strong></td>
		<td align="center"><strong><# MAX_FILESIZE #></strong></td>
		<td align="center"><strong><# FILE_EXTENSIONS #></strong></td>
	</tr>
	</table>
		</div>
<br/>
<div id="uploader" style="width:100%;float:center; height:100px">
		<center><br/><br/><b>If you see this longer than 20 seconds, your browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.<br/> Or some other problem</p></b></center>
</div>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="session" value="<# RAND_SES_VAR #>" />
<br />
<br />
        <if="$mmhclass->info->is_user == true">
                    &bull; Upload Type: <label class="radio"><input type="radio" name="private_upload" value="0" checked="checked" />Public</label> <label class="radio"><input type="radio" name="private_upload" value="1" />Private</label>
                    <br /><br />
	&bull; Output Layout: <label class="radio"><input type="radio" name="upload_type" value="ulfy_standard" <# STANDARD_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=std', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Standard</span></label> <label class="radio"><input type="radio" name="upload_type" value="ulfy_boxed" <# BOXED_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=bx', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Boxed</span></label>
	  <br /><br />
	  </endif>
	  <if="$mmhclass->info->is_user == true && $mmhclass->templ->templ_globals['hide_upload_to'] == false">
                   &bull; Upload To: 
                   <select name="upload_to" style="width: 300px;">
                       <option value="0" selected="selected">Root Album</option>
                          <while id="albums_pulldown_whileloop">
                            <option value="<# ALBUM_ID #>">&bull; <# ALBUM_NAME #></option>
                        </endwhile>
                    </select>
                    <br />
		    &bull; <a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-c', 'new_album_lightbox');" style="font-size:0.8em;">Create New Album</a>
		   <br />
                </endif>
                 <if="$mmhclass->info->is_user == false">
                 <br/>
                 <else>
                 &bull; Resize Images: 
                <select name="image_resize" style="width: 300px;">
			<option value="0" selected="selected">Do Not Resize</option>
			<option disabled="disabled">----</option>
            <option value="1">100x75 (avatar)</option>
			<option value="2">150x112 (thumbnail)</option>
			<option value="3">320x240 (for websites and email)</option>
			<option value="4">640x480 (for message boards)</option>
			<option value="5">800x600 (15-inch monitor)</option>
			<option value="6">1024x768 (17-inch monitor)</option>
			<option value="7">1280x1024 (19-inch monitor)</option>
			<option value="8">1600x1200 (21-inch monitor)</option>
                </select>
        </div></endif>
		<if="$mmhclass->info->config['recaptcha_guest'] == '1' && $mmhclass->info->is_user == false">
                <# CAPTCHA_CODE #>
                <br/>
                <br/>
		<a alt="Upload" valign="center" title="Upload" class="btn button-primary" onclick="checknload();"  /><i class="icon icon-green icon-arrowthick-n"></i> Start Uploading</a>
			<else>
			<br/>
            <br/>
		<button type="submit" alt="Upload" valign="center" title="Upload" class="btn button-primary" /><i class="icon icon-green icon-arrowthick-n"></i> Start Uploading</button>
	 </endif>
	<a class="btn button-primary" href="index.php"><i class="icon-remove"></i> Clear</a>
	<br/><p style="color: red;" id="captchaStatus">&nbsp;</p>

</td>
</tr>
</table>
</form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
</template>
<!-- END: NORMAL UPLOAD PAGE -->

<!-- BEGIN: URL UPLOAD PAGE -->
<template id="url_upload_page">
<if="$mmhclass->info->is_user == false">
<script type="text/javascript" src="source/includes/scripts/rc_comp.js">></script>
</endif>
<script type="text/javascript">
	function set_upload_type(id)
	{
		$("div[id=upload_types] div:visible").attr("style", "display: none;"); 
		$("div[id=" + $(id).val() + "]").attr("style", "display: block;");	
		
		switch ($(id).val()) {
			case "paste_upload":
				$("input[id=more_files_button]").attr("disabled", "disabled");
				$("span[id=more_instructions]").html("<br />Separate each image URL with a new line.");
				$("span[id=instructions]").html("Enter up to <# MAX_RESULTS #> image file URLs to upload");
				break;
			case "normal_upload":
				$("span[id=more_instructions]").html("&nbsp;");
				$("input[id=more_files_button]").removeAttr("disabled");
				$("span[id=instructions]").html("Enter an image file URL to upload");
				break;
			case "webpage_upload":
				$("input[id=more_files_button]").attr("disabled", "disabled");
				$("span[id=instructions]").html("Enter a webpage URL to upload images from");
				$("span[id=more_instructions]").html("<br />Only the first <# MAX_RESULTS #> images that are found will be uploaded.");
				break;
		}
	}
</script>

			<div class="row-fluid">
				<div class="box span13">
					<div class="box-header well">
						<h2><i class="icon-home"></i><strong>Welcome to <# SITE_NAME #>! - URL Upload</strong></h2>
					</div>
					<br/>
					<div class="alert alert-block " style="width: 90%; margin-left: auto; margin-right: auto;">
							<h4 class="alert-heading">Warning!</h4>
							<p>This version is in Alpha testing, there will more than likey be a few bugs so please don't expect too much right now. Thanks.</p>
					</div>
					<div class="box-content">
<form action="upload.php" method="post" id="upload_form" enctype="multipart/form-data">
<table width="100%" cellpadding="2" cellspacing="1">
	<tr>
		<td valign="top">
<span id="instructions">Enter the URL(s) of an image file to upload</span>
<br />
<br />

		<div id="upload_types">
        	<div id="normal_upload">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                <if="$mmhclass->info->config['guest_url'] == '1'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />               
                </endif>
                <if="$mmhclass->info->config['guest_url'] == '2'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />               
                </endif>
                <if="$mmhclass->info->config['guest_url'] == '3'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />                
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '4'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />                
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '5'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />                
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />               
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '6'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />                
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '7'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />              
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '8'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />                
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />               
                </endif>                
                <if="$mmhclass->info->config['guest_url'] == '9'">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                </endif>                
                <if="$mmhclass->info->is_user == true">
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                URL: <input name="userfile[]" type="text" size="55" class="input_field" /> <br />
                <span id="more_file_inputs"></span> <br /></endif>
            </div>
            
            <div id="webpage_upload" style="display: none;">
                URL: <input name="webpage_upload" type="text" size="50" class="input_field" value="http://www.google.com/" onclick="$(this).val('');" />
                <br /><br />
            </div>
            
            <div id="paste_upload" style="display: none;">
           		<textarea name="paste_upload" cols="60" rows="10" class="input_field" style="width: 440px;"></textarea>
                <br /><br />
            </div>
        </div>
        
    	<if="$mmhclass->info->is_user == true">
    	URL Upload Type: 
        <select name="url_upload_type" onchange="set_upload_type(this);">
        	<option value="normal_upload">Normal URL Upload</option>
        	<option value="paste_upload">Paste a List of URLs to Upload</option>
        	<option value="webpage_upload">Upload All Images Linked on a Webpage</option>
        </select>
        <br /><br /></endif>
</td>
	<td valign="top">
		Max filesize is set at: <# MAX_FILESIZE #> per image file.<br />
		Allowed File Extensions: <# FILE_EXTENSIONS #><br /><br />
		                <if="$mmhclass->info->is_user == true">
                    &bull; Upload Type: <label class="radio"><input type="radio" name="private_upload" value="0" checked="checked" /> Public</label> <label class="radio"><input type="radio" name="private_upload" value="1" /> Private</label>
                    <br /><br />
                </endif>
                
                &bull; Output Layout: <label class="radio"><input type="radio" name="upload_type" value="url-standard" <# STANDARD_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=std', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Standard</span></label> <label class="radio"><input type="radio" name="upload_type" value="url-boxed" <# BOXED_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=bx', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Boxed</span></label>
                <br /><br />
                    
                <if="$mmhclass->info->is_user == true && $mmhclass->templ->templ_globals['hide_upload_to'] == false">
                    &bull; Upload To: 
                    <select name="upload_to" style="width: 220px;">
                        <option value="0" selected="selected">Root Album</option>
                        
                        <while id="albums_pulldown_whileloop">
                            <option value="<# ALBUM_ID #>">&bull; <# ALBUM_NAME #></option>
                        </endwhile>
                    </select>
                    <br />
		      &bull; <a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-c', 'new_album_lightbox');" style="font-size:0.8em;">Create New Album</a>
		    <br />
                </endif>
                
                &bull; Resize Images: 
                <select name="image_resize" style="width: 220px;">
                    <option value="0" selected="selected">Do Not Resize</option>
                    
                    <option disabled="disabled">----</option>
                    
                    <option value="1">100x75 (avatar)</option>
                    <option value="2">150x112 (thumbnail)</option>
                    <option value="3">320x240 (for websites and email)</option>
                    <option value="4">640x480 (for message boards)</option>
                    <option value="5">800x600 (15-inch monitor)</option>
                    <option value="6">1024x768 (17-inch monitor)</option>
                    <option value="7">1280x1024 (19-inch monitor)</option>
                    <option value="8">1600x1200 (21-inch monitor)</option>
                </select>
         	<br />
         	      <if="$mmhclass->info->config['recaptcha_guest'] == '1' && $mmhclass->info->is_user == false">
                    <div style="width:100%;text-align:left;"><span>Security Code:</span><# CAPTCHA_CODE #></div>
                    </endif><br />
                    
<if="$mmhclass->info->is_user == true">
<a alt="Add a file" title="Add a file" class="btn button-primary" onclick="new_file_input('url');" /><i class="icon icon-plus"></i> Add a file</a>
    <button alt="Upload" title="Upload" class="btn button-primary" type="submit" onclick="toggle_lightbox('index.php?act=upload_in_progress', 'progress_bar_lightbox'); $('form[id=upload_form]').submit();" /><i class="icon icon-green icon-arrowthick-n"></i> Start Uploading</button></p>
<else>
<button type="submit" alt="Upload" valign="center" title="Upload" class="btn button-primary" onclick="checknload(true);"  /><i class="icon icon-green icon-arrowthick-n"></i> Start Uploading</button>
<br/><p style="color: red;" id="captchaStatus">&nbsp;</p>
</endif>
		</td>
	</tr>
</table></form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
<if="$mmhclass->info->config['show_random'] == '1'">
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Random Images</h2>
					</div>
					<div class="box-content">
<table width="100%">

	<tr>		<td><div id="rnd_imgs"><center>    <while id="random_images_whileloop">
    <# RANDOM_IMAGES #>
    </endwhile></center></div>
    <script type="text/javascript">
      TopUp.addPresets({
        "#rnd_imgs a": {
          title: "Gallery {alt} ({current} of {total})",
          group: "rnd_imgs",
	  layout: "quicklook",
          readAltText: 1,
          shaded: 1
        }
      });
    </script>
</td></tr></table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
</endif>

</template>
<!-- END: URL UPLOAD PAGE -->

<!-- BEGIN: UPLOADER PROGRESS BAR LIGHTBOX -->
<template id="upload_in_progress_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Please Stand By</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center">
            Upload in progress...
            <br /><br />
            <img src="./img/ajax-loaders/ajax-loader-4.gif" alt="Loading..." style="height: 19px;" />
            <br /><br />
            Your images are processed. Please stay tuned!
        </td>
	</tr>
	<tr>
		<td class="table_footer">&nbsp;</td>
	</tr>
</table>

</template>
<!-- END: UPLOADER PROGRESS BAR LIGHTBOX -->

<!-- BEGIN: UPLOAD LAYOUT PREVIEW LIGHTBOX -->
<template id="upload_layout_preview_lightbox">

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th>Upload Layout Preview</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center" height="<# IMAGE_HEIGHT #>px;">
			<a href="css/images/<# PREVIEW_TYPE #>layout_prev.jpg"><img src="css/images/<# PREVIEW_TYPE #>layout_prev.jpg" alt="Upload Layout Preview" /></a>
        </td>
	</tr>
	<tr>
		<td class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

</template>
<!-- END: UPLOAD LAYOUT PREVIEW LIGHTBOX -->

<!-- BEGIN: ZIP UPLOAD PAGE -->

<template id="zip_upload_page">
<if="$mmhclass->info->is_user == false">
<script type="text/javascript" src="source/includes/scripts/rc_comp.js">></script>
</endif>
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-home"></i>Welcome to <# SITE_NAME #>! - Zip Upload</h2>
					</div>
					<br/>
					<div class="alert alert-block " style="width: 90%; margin-left: auto; margin-right: auto;">
							<h4 class="alert-heading">Warning!</h4>
							<p>This version is in Alpha testing, there will more than likey be a few bugs so please don't expect too much right now. Thanks.</p>
					</div>
					<div class="box-content">
<form action="upload.php" method="post" id="upload_form" enctype="multipart/form-data">
<table width="100%">
	<tr>
		<td valign="top">
		<span id="instructions">Choose a ZIP file to upload!</span>
		<br />
		<br />
			<input name="userfile" type="file" size="50" /> <br />
		</td>
		<td width="425" valign="top">
		Max filesize is set at: <# MAX_FILESIZE #> per image file.<br />
		Allowed Archive File Extension: .zip<br/>
		Allowed File Extensions in Archive: <# FILE_EXTENSIONS #><br /><br />
                <if="$mmhclass->info->is_user == true">
                    &bull; Upload Type: <input type="radio" name="private_upload" value="0" checked="checked" /> Public <input type="radio" name="private_upload" value="1" /> Private
                    <br /><br />
                </endif>
                &bull; Output Layout: <input type="radio" name="upload_type" value="zip_standard" <# STANDARD_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=std', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Standard</span> <input type="radio" name="upload_type" value="zip-boxed" <# BOXED_UPLOAD_YES #> /> <span onclick="toggle_lightbox('index.php?layoutprev=bx', 'upload_layout_preview_lightbox');" title="Click to preview" class="help">Boxed</span>
                <br /><br />
                <if="$mmhclass->info->is_user == true && $mmhclass->templ->templ_globals['hide_upload_to'] == false">
                    &bull; Upload To: 
                    <select name="upload_to" style="width: 220px;">
                        <option value="0" selected="selected">Root Album</option>
			<while id="albums_pulldown_whileloop">
                            <option value="<# ALBUM_ID #>">&bull; <# ALBUM_NAME #></option>
                        </endwhile>
                    </select>
                    <br />
		      &bull; <a href="javascript:void(0);" onclick="toggle_lightbox('users.php?act=albums-c', 'new_album_lightbox');" style="font-size:0.8em;">Create New Album</a>
		    <br />
                </endif>
                &bull; Resize Images: 
                <select name="image_resize" style="width: 220px;">
                    <option value="0" selected="selected">Do Not Resize</option>
                    <option disabled="disabled">----</option>
                    <option value="1">100x75 (avatar)</option>
                    <option value="2">150x112 (thumbnail)</option>
                    <option value="3">320x240 (for websites and email)</option>
                    <option value="4">640x480 (for message boards)</option>
                    <option value="5">800x600 (15-inch monitor)</option>
                    <option value="6">1024x768 (17-inch monitor)</option>
                    <option value="7">1280x1024 (19-inch monitor)</option>
                    <option value="8">1600x1200 (21-inch monitor)</option>
                </select>
        </div><br /><br />
                                    <if="$mmhclass->info->config['recaptcha_guest'] == '1' && $mmhclass->info->is_user == false">
                    <div style="width:100%;text-align:left;"><span>Security Code:</span><# CAPTCHA_CODE #></div>
                    </endif><br /><br />
		<if="$mmhclass->info->is_user == false">
		<img border="0" src="./css/images/upload.png" alt="Upload"  valign="center" title="Upload" class="button1"  value="Start Uploading" onclick="checknload(true);"  />
		<br/><p style="color: red;" id="captchaStatus">&nbsp;</p>
		<else>
		 <img border="0" src="./css/images/upload.png" alt="Upload" title="Upload" class="button1" type="button" value="Start Uploading" onclick="toggle_lightbox('index.php?act=upload_in_progress', 'progress_bar_lightbox'); $('form[id=upload_form]').submit();" />
		</endif>
		</td></tr>
</table></form>
						<div class="clearfix"></div>
					</div>
				</div>
			</div><if="$mmhclass->info->config['show_random'] == '1'">
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Random Images</h2>
					</div>
					<div class="box-content">
<table width="100%">

	<tr>		<td><div id="rnd_imgs"><center>    <while id="random_images_whileloop">
    <# RANDOM_IMAGES #>
    </endwhile></center></div>
    <script type="text/javascript">
      TopUp.addPresets({
        "#rnd_imgs a": {
          title: "Gallery {alt} ({current} of {total})",
          group: "rnd_imgs",
	  layout: "quicklook",
          readAltText: 1,
          shaded: 1
        }
      });
    </script>
</td></tr></table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
</endif>
</template>

<!-- END: ZIP UPLOAD PAGE -->

<!-- BEGIN: USER UPLOAD LOGIN PAGE -->
<template id="upl_login_page">
<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-home"></i>
						<strong>Welcome to <# SITE_NAME #>!</strong>
						</h2>
					</div>
					<br/>						
		<div class="container-fluid">
			
			<div class="row-fluid">
				<div class="well span4 center login-box">
<form class="form-horizontal" action="users.php?act=login-d" method="post">
	<input type="hidden" name="return" value="<# RETURN_URL #>" />
						<fieldset>
							<div class="input-prepend" title="Username" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="username" id="username" type="text" size="50" />
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="Password" data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="password" id="password" type="password" size="50" />
							</div>
							<div class="clearfix"></div>

							<p class="center span4">
							<button type="submit" class="btn btn-small btn-primary">Log In</button>
							</p>
							<div class="clearfix"></div>
							
							<p class="center">
							Forget your password? Click <a href="#" class="pwreset">here</a>.
							</p>
						</fieldset>
					</form>
				</div><!--/span-->
			</div><!--/row-->
				</div><!--/fluid-row-->
				</div></div>
				<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-home"></i>
						<strong><# SITE_NAME #> Members Information!</strong>
						</h2>
					</div>
					<br/>						
		<div class="container-fluid">
			
			<div class="row-fluid">
<table border="0" width="100%">
	<tr>
		<td align="center"><strong><# SITE_NAME #> Galleries</strong></td>
		<td align="center"><strong>Extra information</strong></td>
	</tr>
	<tr>
		<td align="center">Create your own galleries at <# SITE_NAME #> free of charge.<br/>
		Unlimited galleries, unlimited upload and unlimited views.<br/>
		</td>
		<td align="center">By uploading your files to <# SITE_NAME #> you agree to our <a href="info.php?act=rules">ToS</a><br/><br/>
		Max filesize per image <# MAX_FILESIZE #><br/>
		Allowed File Extensions <# FILE_EXTENSIONS #><br/><br/>
		If your filetype is not supported, please send an email using the <a href="contact.php?act=contact_us">Contact Us</a> and we will look into the matter. 
		</td>
	</tr>
</table>
								</div><!--/span-->
			</div><!--/row-->
				</div><!--/fluid-row--></div>
						</div>
</template>
<!-- END: USER UPLOAD LOGIN PAGE -->