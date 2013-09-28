/* ======================================== \
// Package: Mihalism Multi Host
// Version: 5.0.0
// Copyright (c) 2007, 2008, 2009 Mihalism Technologies
// License: http://www.gnu.org/licenses/gpl.txt GNU Public License
// LTE: 1251484174 - Friday, August 28, 2009, 02:29:34 PM EDT -0400
// ======================================= */

var lang = new Array();
var page_url = location.href;
var index_amf_max = 15;
var index_amf_total  = 0;
/* reCAPTCHA Settings */
var RecaptchaOptions = { theme : 'white' }; 

/* Language Settings */

lang['001'] = "Click to add title"; // File title if no title is set
lang['002'] = "Remove File"; // Notice to be used to remove a image upload field
lang['003'] = "You can only add a max of 15 files to each upload."; // Error to be displayed if too many new image upload fields are added
lang['004'] = "URL:"; // Notice to be used to indicate a image upload field is for an URL
lang['005'] = "Select at least one image."; // Error to be displayed if no images are selected to delete or move on button press
lang['006'] = "Check Again"; // Notice to be used when to recheck username availablity 
lang['007'] = "Username Not Available."; // Notice to be used when an username is not available
lang['008'] = "Username Available!"; // Notice to be used when an username is available

/* DO NOT EDIT PAST THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING */

preload_image("css/images/site_logo.jpg", 970, 140);
preload_image("css/images/main_bg.png", 120, 450);
preload_image("css/images/blue_box_bg.gif", 12, 110);
preload_image("css/images/input_bg.gif", 30, 25);
preload_image("css/images/nav_mem_bar.gif", 1, 30);
preload_image("css/images/pc_foot_bg.gif", 6, 25);
preload_image("css/images/progress_bar.gif", 32, 32);
preload_image("css/images/tbl_foot_bg.gif", 8, 38);
preload_image("css/images/tbl_top_bg.gif", 8, 25);
preload_image("css/images/bxlayout_prev.png", 500, 462);
preload_image("css/images/stdlayout_prev.png", 500, 283);

function preload_image(path, width, height) 
{
	if (document.images) {
		image_file = new Image(width, height); 
		image_file.src = path;
	}
}

function google_stats(id)
{
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	try {
		var pageTracker = _gat._getTracker(id);
		pageTracker._trackPageview();
	} catch(err) {}	
}

function get_ajax_content(theurl) 
{
	return $.ajax({
  		url: theurl,
		async: false,
		type: "GET"
	}).responseText;
}

function gallery_action(act, id, value)
{	
	switch (act) {
		case "select":
			$("input[name=userfile]").each(function()
			{
				this.checked = ((this.checked == 1) ? 0 : 1);
			});      
			break;
		case "rename":
			var current_title = $("span[id=" + id + "]").html();
			$("span[id=" + id + "]").toggle();
			$("input[id=" + id + "_rename]").toggle();
			$("input[id=" + id + "_rename]").val(current_title);
			$("input[id=" + id + "_rename]").focus();
			$("input[id=" + id + "_rename]").select();
			break;
		case "rename-d":
			var the_title = $("input[id=" + value + "_title_rename]").val();
			var new_title = ((the_title == "") ? lang['001'] : the_title);
			var data = get_ajax_content("users.php?act=rename_file_title&file=" + id + "&title=" + encodeURI(new_title));
			/* The jQuery toggle() method is not used to fix a known bug. */
			$("input[id=" + value + "_title_rename]").attr("style", "display: none;");
			$("span[id=" + value + "_title]").attr("style", "display: inline;");
			$("span[id=" + value + "_title]").html(data);
			break;
		case "move":
		case "make_ext_gal":
		case "delete":
			var checkedfiles = "";
			$("input[name=userfile]").each(function()
			{
				if (this.checked == 1) {
					checkedfiles += (this.value + ",");
				}
			});      
			if (checkedfiles !== "") {
				checkedfiles = checkedfiles.substr(0, (checkedfiles.length - 1));
				toggle_lightbox("users.php?act=" + act + "_files&files=" + encodeURI(checkedfiles) + "&return=" + encodeURIComponent(page_url), (act + "_files_lightbox"));
			} else {
				alert(lang['005']);
			}
			break;
		}
	return;
}

function center_screen(id)
{
	// Lots of variables
	var elemwidth = $(id).width();
    var elemheight = $(id).height();
    var windowwidth = $(window).width();
    var windowheight = $(window).height();
	var precentuse = ((elemheight / windowheight ) * 100);
   	var theheight = ((windowheight - elemheight) / 2) - ((precentuse > 50) ? 2.5 : 50);
    var thewidth = ((windowwidth - elemwidth) / 2);
    var finalheight = ((((theheight < 1) ? 0 : theheight) + $(window).scrollTop()) + "px");
    var finalwidth = (((thewidth < 1) ? 0 : thewidth) + "px");
    return ("top: " + finalheight + "; left: " + finalwidth + ";");
}

function toggle_lightbox(url, div)
{
	if (url !== "no_url") {
		//scroll(0, 0);
		$("#page_body").append("<div id=\"" + div + "\"></div>");
		var data = get_ajax_content((url + (((url.match(/\?/)) ? "&" : "?") + "lb_div=" + div + "&return=" + base64_encode(page_url))));
		$("div[id=" + div + "]").html("<div class=\"lightbox_main\">" + data + "</div>" +
				"<div class=\"lightbox_background\">&nbsp;</div>");
		$("div[class=lightbox_main]").attr("style", center_screen("div[class=lightbox_main]"));
		$("div[class=lightbox_background]").css("width", $(document).width());
		$("div[class=lightbox_background]").css("height", $(document).height());
	} else {
		$("div[id=" + div + "]").remove();
	}
	return;
}

function check_username()
{
	var username = $("#username_field").val();    
	var data = get_ajax_content("users.php?act=check_username&username=" + encodeURI(username));
	if (data == "1" || username == "") {
		$("#username_check").html("<img src=\"css/images/xed_out.gif\" alt=\"" + lang['007'] + "\" style=\"vertical-align: -20%;\" /> <span style=\"color: #C33;\">" + lang['007'] + "</span> - <a href=\"javascript:void(0)\" onclick=\"check_username();\">" + lang['006'] + "</a>");
	} else {
		$("#username_check").html("<img src=\"css/images/green_check.gif\" alt=\"" + lang['008'] + "\" style=\"vertical-align: -20%;\" /> <span style=\"color: #096;\">" + lang['008'] + "</span> - <a href=\"javascript:void(0)\" onclick=\"check_username();\">" + lang['006'] + "</a>");
	}
	return;
}

function highlight(field) 
{
	field.focus();
	field.select();
	return;
}

function toggle(id) 
{
	$("#" + id).toggle();
	return;
}

function new_file_input(upload_type) 
{
	if (index_amf_total < index_amf_max) {
		if (upload_type == "url") {
			$("#more_file_inputs").append("<div id=\"file-" +  index_amf_total + "\">" +
						lang['004'] + " <input name=\"userfile[]\" type=\"text\" size=\"55\" class=\"input_field\" /> " +
						"<span class=\"button2\" onclick=\"remove_file_input('file-" + index_amf_total + "');\">" + lang['002'] + "</span> <br />" +
						"</div>");
		} else {
			$("#more_file_inputs").append("<div id=\"file-" +  index_amf_total + "\">" +
						"<input name=\"userfile[]\" type=\"file\" size=\"50\" /> " +
						"<span class=\"button2\" onclick=\"remove_file_input('file-" + index_amf_total + "');\">" + lang['002'] + "</span> <br />" +
						"</div>");
		}
		index_amf_total++;
	} else {
		alert(lang['003']);
	}
	return;
}

function remove_file_input(div)
{
	$("#" + div).remove();
	return;
}