
<# PWRST_HTML #>
<# ABTCH_HTML #>

		<hr />

		<footer>
			<p class="pull-left">
    <if="$mmhclass->info->config['seo_urls'] == 1">
        <a href="privacy_policy.html">Privacy Policy</a> | <a href="about_us.html">About Us</a> | <a href="rules.html">ToS</a> | <a href="file_report.html">Report Abuse</a> | <a href="contact_us.html">Contact Us</a>
<else>	
        <a href="info.php?act=privacy_policy">Privacy Policy</a> | <a href="info.php?act=about_us">About Us</a> | <a href="info.php?act=rules">ToS</a> | <a href="contact.php?act=file_report">Report Abuse</a> | <a href="contact.php?act=contact_us">Contact Us</a>
    </endif>
<br />
Page Views: <# TOTAL_PAGE_VIEWS #>
			</p>
			<p class="pull-right"><# COPYRIGHT #>
    <if="$mmhclass->funcs->is_null(" <# GOOGLE_ANALYTICS_ID #> ") == false">
    <br />
		<script type="text/javascript">
			google_stats("<# GOOGLE_ANALYTICS_ID #>");
 		</script>
    </endif>
			</p>
		</footer>
		
	</div><!--/.fluid-container-->
</body>
</html>
