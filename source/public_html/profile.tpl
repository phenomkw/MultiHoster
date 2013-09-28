<!-- BEGIN: USER PROFILE SEARCH PAGE -->
<template id="profile_search_page">
<center>
<h2>Which user's profile do you want to view?</h2><br/>Insert ID:<br />
<form action="profile.php" method="get" id="username_form">
<input type="text" style="width: 150px;" name="id" class="input_field" />
<input type="submit" value="View" class="button1" />
</center>
</template>
<!-- END: USER PROFILE SEARCH PAGE -->

<!-- BEGIN: USER PROFILE PAGE -->
<template id="profile_page">
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> 
						<# USER_NAME #>'s Profile
						</h2>
					</div>
					<div class="box-content">
		<table cellpadding="4" cellspacing="1" border="0" style="width: 100%;" class="table">
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>E-Mail Address</span>:</td>
				<td class="tdrow2" valign="top"><# EMAIL_ADDRESS #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%"><span>View <# USER_NAME #>'s  Gallery</span>: <br /></td>
				<td class="tdrow2" valign="top"><# GAL_LINK #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Member since</span>:</td>
				<td class="tdrow2"><# TIME_JOINED #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>My Country</span>:</td>
				<td class="tdrow2" valign="top"><# FROM_COUNTRY #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>My City</span>:</td>
				<td class="tdrow2" valign="top"><# FROM_CITY #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Homepage URL</span>:</td>
				<td class="tdrow2" valign="top"><# HOMEPAGE_URL #></td>
			</tr>
			<tr>
				<td class="tdrow1" style="width: 38%;"><span>Facebook Profile</span>:</td>
				<td class="tdrow2" valign="top"><# FACEBOOK_PAGE #></td>
			</tr>
		</table>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

</template>
<!-- END: USER PROFILE PAGE -->