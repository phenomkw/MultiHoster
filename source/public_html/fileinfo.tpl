<!-- BEGIN: FILE INFO LIGHTBOX -->

<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;">
	<tr>
		<th colspan="2">Image Meta Information & Preview</th>
	</tr>
	<tr>
		<td class="tdrow1 text_align_center" colspan="2" height="<# THUMBNAIL_HEIGHT #>px;">
		<if="$mmhclass->info->config['seo_urls'] == '0'">
        	<a href="img.php?image=<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150"><img src="index.php?module=thumbnail&amp;file=<# FILENAME #>" alt="<# FILENAME #> Thumbnail" /></a>
		<else>
		<a href="<# FILENAME #>" class="top_up" toptions="effect = clip, shaded = 1, layout = flatlook, y = 150"><img src="thumb.<# FILENAME #>" alt="<# FILENAME #> Thumbnail" /></a>
		</endif>
        </td>
	</tr>
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Original Filename:</span></td>
		<if="$mmhclass->info->config['seo_urls'] == '0'">
		<td class="tdrow2"><a href="viewer.php?file=<# FILENAME #>"><# REAL_FILENAME #></a></td>
		<else>
		<td class="tdrow2"><a href="<# FILENAME #>.html"><# REAL_FILENAME #></a></td>
		</endif>
	</tr>
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Dimensions:</span></td>
		<td class="tdrow2"><# IMAGE_WIDTH #> x <# IMAGE_HEIGHT #> Pixels (Width by Height)</td>
	</tr>
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Mime Type:</span></td>
		<td class="tdrow2"><a href="http://www.fileinfo.com/extension/<# FILE_EXTENSION #>" target="_new" rel="nofollow"><# MIME_TYPE #></a></td>
	</tr>
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Date Uploaded:</span></td>
		<td class="tdrow2"><# DATE_UPLOADED #></td>
	</tr>
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Total Filesize:</span></td>
		<td class="tdrow2"><# TOTAL_FILESIZE #></td>
	</tr>
	<if="$mmhclass->info->config['seo_urls'] == '0'">
	<if="$mmhclass->info->config['hotlink'] == '1'">
    <tr>
           <td style="width: 44%;" class="tdrow1"><span class="arial">Direct Link:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="<# BASE_URL #>img.php?image=<# FILENAME #>" /></td>
       </tr>
       </endif>
       <tr>
          <td style="width: 44%;" class="tdrow1"><span class="arial">Thumbnail for Website:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="&lt;a href=&quot;<# BASE_URL #>viewer.php?file=<# FILENAME #>&quot;&gt;&lt;img src=&quot;<# BASE_URL #>img.php?image=<# FILENAME #>&mode=thumb&quot; border=&quot;0&quot; alt=&quot;<# FILENAME #>&quot; /&gt;&lt;/a&gt;" /></td>
       </tr>
       <tr>
          <td style="width: 44%;" class="tdrow1"><span class="arial">Thumbnail for Forum:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="[URL=<# BASE_URL #>viewer.php?file=<# FILENAME #>][IMG]<# BASE_URL #>img.php?image=<# FILENAME #>&mode=thumb[/IMG][/URL]" /></td>
       </tr>
	<else>
	<if="$mmhclass->info->config['hotlink'] == '1'">
	   <tr>
           <td style="width: 44%;" class="tdrow1"><span class="arial">Direct Link:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="<# BASE_URL #><# FILENAME #>" /></td>
       </tr>
       </endif>
       <tr>
          <td style="width: 44%;" class="tdrow1"><span class="arial">Thumbnail for Website:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="&lt;a href=&quot;<# BASE_URL #><# FILENAME #>.html&quot;&gt;&lt;img src=&quot;<# BASE_URL #>thumb.<# FILENAME #>&quot; border=&quot;0&quot; alt=&quot;<# FILENAME #>&quot; /&gt;&lt;/a&gt;" /></td>
       </tr>
       <tr>
          <td style="width: 44%;" class="tdrow1"><span class="arial">Thumbnail for Forum:</span></td>
          <td><input readonly="readonly" class="input_field" onclick="highlight(this);" type="text" style="width: 300px;" value="[URL=<# BASE_URL #><# FILENAME #>.html][IMG]<# BASE_URL #>thumb.<# FILENAME #>[/IMG][/URL]" /></td>
       </tr>
       </endif>
  <tr>
      <if="$mmhclass->info->is_admin == true && $mmhclass->info->config['proxy_images'] == true">
        <tr>
            <td style="width: 44%;" class="tdrow1"><span class="arial">Bandwidth Usage:</span></td>
            <td class="tdrow2"><# BANDWIDTH_USAGE_FORMATTED #></td>
        </tr>
    </endif>
    
	<tr>
		<td style="width: 44%;" class="tdrow1"><span class="arial">Rating:</span></td>
		<td class="tdrow2"><img src="index.php?module=rating&file=<# FILENAME #>" style="vertical-align: -20%" alt="File Rating" /></td>
	</tr>
	<tr>
		<td colspan="2" class="table_footer"><a onclick="toggle_lightbox('no_url', '<# LIGHTBOX_ID #>');">Close Window</a></td>
	</tr>
</table>

<!-- END: FILE INFO LIGHTBOX -->