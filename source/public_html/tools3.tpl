<!-- BEGIN: IFRAME UPLOADER -->
<template id="iframe_uploader3">

<style type="text/css">
	#uploader_iframe3 { margin: 0; padding: 0; font-size: 12px; background-color: transparent; font-family: Arial, Verdana, Helvetica, sans-serif; }
	#uploader_iframe3 a:link, #uploader_iframe a:visited { color: #005fa9; text-decoration: none; }
	#uploader_iframe3 a:hover { text-decoration: underline; }
</style>

<span id="uploader_iframe3">
    <form action="<# BASE_URL #>upload.php" target="_blank" method="post" enctype="multipart/form-data">
        <if="$mmhclass->templ->templ_globals['upload_type'] == 'url'">
            <input type="text" name="userfile[]" style="width: 220px;" value="Enter image URL to upload." onclick="$(this).val('');" />
            <input type="hidden" name="upload_type" value="url-standard" />
	      <input type="hidden" name="source" value="iframe" />
            <input type="submit" value="Upload"> - <a href="javascript:void(0);" onclick="$('#iframe_uploader3').html(get_ajax_content('<# BASE_URL #>index.php?module=iframeupload3'));">Normal Upload</a>
        <else> 
            <input type="file" name="userfile[]" size="27" /> 
            <input type="hidden" name="upload_type" value="standard" />
	      <input type="hidden" name="source" value="iframe" />
            <input type="submit" value="Upload"> - <a href="javascript:void(0);" onclick="$('#iframe_uploader3').html(get_ajax_content('<# BASE_URL #>index.php?module=iframeupload3&url=1'));">URL Upload</a>
        </endif>
    </form>
</span>

</template>
<!-- END: IFRAME UPLOADER -->

<!-- BEGIN: TOOLS PAGE -->
<template id="tools_page3">
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						iFrame Uploader +URL
					</li>
				</ul>
			</div>

			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> iFrame Uploader +URL</h2>
					</div>
					<div class="box-content">
The <# SITE_NAME #> iFrame Uploader is a simple tool that can be embeded into any blog, forum, or website to make uploading
directly to <# SITE_NAME #> fast and easy. The uploader intergrates into any website so you do not have to browse to <# SITE_NAME #>
to upload an image.
<br /><br />
<strong>Preview:</strong> <span id="iframe_uploader3"></span><script type="text/javascript">$('#iframe_uploader3').html(get_ajax_content("<# BASE_URL #>index.php?module=iframeupload3"));</script>
<br /><br />
<strong>Source Code</strong>:<br />
<textarea cols="90" rows="8" onclick="highlight(this);" readonly="readonly" class="input_field">
&lt;iframe allowtransparency=&quot;yes&quot; frameborder=&quot;0&quot; scrolling=&quot;no&quot; src=&quot;<# BASE_URL #>index.php?module=iframeupload3&quot; style=&quot;height: 40px; width: 500px;&quot;&gt;Your browser does not support iframes.&lt;/iframe&gt;
</textarea>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: TOOLS PAGE -->