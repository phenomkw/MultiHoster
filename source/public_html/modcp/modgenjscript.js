var page_url=location.href,RecaptchaOptions={theme:"white"};preload_image("css/images/site_logo.jpg",970,140);preload_image("css/images/main_bg.png",120,450);preload_image("css/images/blue_box_bg.gif",12,110);preload_image("css/images/input_bg.gif",30,25);preload_image("css/images/nav_mem_bar.gif",1,30);preload_image("css/images/pc_foot_bg.gif",6,25);preload_image("css/images/progress_bar.gif",32,32);preload_image("css/images/tbl_foot_bg.gif",8,38);preload_image("css/images/tbl_top_bg.gif",8,25);
function preload_image(a,b,c){if(document.images)image_file=new Image(b,c),image_file.src=a}function google_stats(a){document.write(unescape("%3Cscript src='"+("https:"==document.location.protocol?"https://ssl.":"http://www.")+"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));try{_gat._getTracker(a)._trackPageview()}catch(b){}}function get_ajax_content(a){return $.ajax({url:a,async:!1,type:"GET"}).responseText}
function gallery_action(a,b,c){switch(a){case "select":$("input[name=userfile]").each(function(){this.checked=this.checked==1?0:1});break;case "rename":c=$("span[id="+b+"]").html();$("span[id="+b+"]").toggle();$("input[id="+b+"_rename]").toggle();$("input[id="+b+"_rename]").val(c);$("input[id="+b+"_rename]").focus();$("input[id="+b+"_rename]").select();break;case "rename-d":a=$("input[id="+c+"_title_rename]").val();b=get_ajax_content("modcp.php?act=rename_file_title&file="+b+"&title="+encodeURI(a==
""?"Click to add title":a));$("input[id="+c+"_title_rename]").attr("style","display: none;");$("span[id="+c+"_title]").attr("style","display: inline;");$("span[id="+c+"_title]").html(b);break;case "move":case "delete":var d="";$("input[name=userfile]").each(function(){this.checked==1&&(d+=this.value+",")});d!==""?(d=d.substr(0,d.length-1),toggle_lightbox("modcp.php?act="+a+"_files&files="+encodeURI(d)+"&id="+b+"&return="+encodeURIComponent(page_url),a+"_files_lightbox")):alert("Select at least one image.")}}
function center_screen(a){var b=$(a).width(),c=$(a).height(),a=$(window).width(),d=$(window).height(),c=(d-c)/2-(c/d*100>50?2.5:50),b=(a-b)/2;return"top: "+((c<1?0:c)+"px")+"; left: "+((b<1?0:b)+"px")+";"}
function toggle_lightbox(a,b){if(a!=="no_url"){scroll(0,0);$("#page_body").append('<div id="'+b+'"></div>');var c=get_ajax_content(a+((a.match(/\?/)?"&":"?")+"lb_div="+b+"&return="+base64_encode(page_url)));$("div[id="+b+"]").html('<div class="lightbox_main">'+c+'</div><div class="lightbox_background">&nbsp;</div>');$("div[class=lightbox_main]").attr("style",center_screen("div[class=lightbox_main]"));$("div[class=lightbox_background]").css("width",$(document).width());$("div[class=lightbox_background]").css("height",
$(document).height())}else $("div[id="+b+"]").remove()}
function check_username(){var a=$("#username_field").val();get_ajax_content("modcp.php?act=check_username&username="+encodeURI(a))=="1"||a==""?$("#username_check").html('<img src="css/images/xed_out.gif" alt="Username Taken" style="vertical-align: -20%;" /> <span style="color: #C33;">Username Not Available.</span> - <a href="javascript:check_username();">Check Again</a>'):$("#username_check").html('<img src="css/images/green_check.gif" alt="Username Available" style="vertical-align: -20%;" /> <span style="color: #096;">Username Available!</span> - <a href="javascript:check_username();">Check Again</a>')}
function highlight(a){a.focus();a.select()}function toggle(a){$("#"+a).toggle()};