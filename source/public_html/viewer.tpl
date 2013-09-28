<!-- BEGIN: MAIN VIEWER PAGE -->
<!-- Facebook Comments Plugin -->
<if="$mmhclass->info->config['facebook_comments'] == 1">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</endif>
<!-- Facebook Comments Plugin -->
<br />
<if="$mmhclass->templ->templ_globals['new_file_rating'] == true">
	<# NEW_RATING_HTML #><hr />
</endif>

<div class="text_align_center">
	<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->funcs->is_null($mmhclass->input->get_vars['is_random']) == false">
		<a href="index.php?do_random=1" class="button1">New Random Image</a>
        <br /><br />
	</endif>
	<else>
	<if="$mmhclass->funcs->is_null($mmhclass->input->get_vars['is_random']) == false">
		<a href="random-image" class="button1">New Random Image</a>
        <br /><br />
	</endif>
	</endif>
	<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->templ->templ_globals['file_info']['width'] > 940">
	<a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150"><img src="img.php?image=<# FILENAME #>" alt="<# REAL_FILENAME #>" style="border: 1px dashed #000000; padding: 2px; max-width:940px;" /></a>

	
		<br /><br />
		<b>Resize</b>: The above image has been resized to better fit your screen. To view its <a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150">true size</a>, click it.
	<else>
	<img src="img.php?image=<# FILENAME #>" alt="<# REAL_FILENAME #>" style="border: 1px dashed #000000; padding: 2px;max-width:940px;" />
	</endif>
	<else>
	<if="$mmhclass->templ->templ_globals['file_info']['width'] > 940">
	<a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150"><img src="<# FILENAME #>" alt="<# REAL_FILENAME #>" style="border: 1px dashed #000000; padding: 2px; max-width:940px;" /></a>

	
		<br /><br />
		<b>Resize</b>: The above image has been resized to better fit your screen. To view its <a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150">true size</a>, click it.
	<else>
	<img src="<# FILENAME #>" alt="<# REAL_FILENAME #>" style="border: 1px dashed #000000; padding: 2px;max-width:940px;" />
	</endif>
	</endif>
</div><hr />

<div class="align_left_mfix">
    <a href="download.php?file=<# FILENAME #>" class="button1">Download Image</a> 
    <a href="contact.php?act=file_report&amp;file=<# FILENAME #>" class="button1">Report Image</a> 
    <span onclick="toggle('file_rating_block');" class="button1">Rate Image</span>
    <span onclick="toggle('file_info');" class="button1">Image Links</span>
    
    <if="$mmhclass->info->is_user == true">
    	<a href="javascript:void(0);" onclick="toggle_lightbox(('admin.php?act=delete_files&amp;files=' + encodeURIComponent('<# FILENAME #>')), 'delete_files_lightbox');" class="button1">Delete Image</a>
    </endif>
</div>

<!-- Facebook Comments Plugin -->
<if="$mmhclass->info->config['facebook_comments'] == 1">
<br />
	<div class="table_border">
		<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
			<tr>
				<th colspan="2">Comments</th>
			</tr>
		</table>
		<div id="comments_block"><table width="100%">
<tr>
				<td style="width: 100%;" class="tdrow1" align="center">

                 <div class="fb-comments" data-href="<# PAGE_URL #>" data-num-posts="4" data-width="670"></div>   

				</td>
			</tr>
			<tr>
				<td colspan="2" class="table_footer">&nbsp;</td>
			</tr>
	</table>
	</div>
</div>
</endif>
<!-- Facebook Comments Plugin -->

<br />
	<div id="file_rating_block" style="display: none;">
		<table cellpadding="4" cellspacing="1" border="0" class="table">
			<tr>
				<th colspan="2">File Rating</th>
			</tr>
		</table>
		<div><table width="100%">
<tr>
				<td style="width: 44%;" class="tdrow1">&nbsp;</td>
				<td style="width: 56%;" class="tdrow1">
					Rate This Image: 
					<br /><br />
                    
					<form action="viewer.php?act=rate_it&amp;file=<# FILENAME #>" method="post">
						<p>
							<input type="radio" name="rating_id" value="5" /> <img src="css/images/ratings/22222.png" alt="Excellent!" style="vertical-align: -20%;" /> Excellent!<br />
							<input type="radio" name="rating_id" value="4" /> <img src="css/images/ratings/22220.png" alt="Very Good" style="vertical-align: -20%;" /> Very Good<br />
							<input type="radio" name="rating_id" value="3" checked="checked" /> <img src="css/images/ratings/22200.png" alt="Good" style="vertical-align: -20%;" /> Good<br />
							<input type="radio" name="rating_id" value="2" /> <img src="css/images/ratings/22000.png" alt="Fair" style="vertical-align: -20%;" /> Fair<br />
							<input type="radio" name="rating_id" value="1" /> <img src="css/images/ratings/20000.png" alt="Poor" style="vertical-align: -20%;" /> Poor
							<br /><br />
                            
							<input type="submit" value="Rate It!" class="button1" />
							<input type="button" value="Cancel" class="button1" onclick="toggle('file_rating_block');" />
						</p>
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="table_footer">&nbsp;</td>
			</tr>
	</table>
	</div>
</div>
<br />

<div id="file_info" style="display: none;">
	<table cellpadding="4" cellspacing="1" border="0" class="table">
		<tr>
			<th colspan="2">Image Information</th>
		</tr>
<tr>
			<td style="width: 44%" class="tdrow1"><span class="arial">Original Filename:</span></td>
			<if="$mmhclass->info->config['seo_urls'] == '0'">
			<td class="tdrow2"><a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><# REAL_FILENAME #></a></td>
			<else>
			<td class="tdrow2"><a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook"><# REAL_FILENAME #></a></td>
			</endif>
		</tr>
		<tr>
			<td style="width: 44%" class="tdrow1"><span class="arial">Dimensions:</span></td>
			<td class="tdrow2"><# IMAGE_WIDTH #> x <# IMAGE_HEIGHT #> Pixels (Width by Height)</td>
		</tr>
        
		<if="<# VIEWER_CLICKS #> > 1">
			<tr>
				<td style="width: 44%" class="tdrow1"><span class="arial">Clicks to Page:</span></td>
				<td class="tdrow2"><# VIEWER_CLICKS #> External Clicks</td>
			</tr>
		</endif>
        
        <if="<# IMAGE_VIEWS #> > 1 && $mmhclass->info->config['proxy_images'] == true">
			<tr>
				<td style="width: 44%" class="tdrow1"><span class="arial">Image Views:</span></td>
				<td class="tdrow2"><# IMAGE_VIEWS #></td>
			</tr>
        </endif>
        
		<if="$mmhclass->funcs->is_null($mmhclass->templ->templ_globals['file_info']['comment']) == true">
			<tr>
				<td style="width: 44%" class="tdrow1"><span class="arial">Mime Type:</span></td>
				<td class="tdrow2"><# MIME_TYPE #></td>
			</tr>
		<else>
			<tr>
				<td style="width: 44%" class="tdrow1"><span class="arial">Meta Comment:</span></td>
				<td class="tdrow2"><# HIDDEN_COMMENT #></td>
			</tr>
		</endif>
		
		<tr>
			<td style="width: 44%" class="tdrow1"><span class="arial">Date Uploaded:</span></td>
			<td class="tdrow2"><# DATE_UPLOADED #></td>
		</tr>
		<tr>
			<td style="width: 44%" class="tdrow1"><span class="arial">Total Filesize:</span></td>
			<td class="tdrow2"><# TOTAL_FILESIZE #></td>
		</tr>
		<tr>
			<td style="width: 44%" class="tdrow1"><span class="arial">Total Rating:</span></td>
			<td class="tdrow2"><img src="index.php?module=rating&amp;file=<# FILENAME #>" style="vertical-align: -20%;" alt="File Rating" /> ( <# TOTAL_RATINGS #> Votes )</td>
		</tr>
		<tr>
			<td colspan="2"><# FILE_LINKS #></td>
		</tr>
	</table>
</div>
</div>
<!-- END: MAIN VIEWER PAGE -->