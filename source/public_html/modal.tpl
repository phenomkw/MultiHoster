<!-- BEGIN: PASSWORD RESET MODAL -->
<template id="pwrst_modal">

<!-- Example Modal -->

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>

<!-- End Example -->

		<div class="modal hide fade" id="rstpw">
			<div class="modal-header">
				<h3>Password Reset</h3>
			</div>
			<div class="modal-body">
			<form action="users.php?act=lost_password-d" method="post">
							<div class="input-prepend" title="Username" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="username" id="username" type="text" />
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="E-Mail Address" data-rel="tooltip">
								<span class="add-on"><i class="icon-envelope"></i></span><input class="input-large span10" name="email_address" id="email_address" type="text" />
							</div>
							<div class="clearfix"></div>
			</div>
			<div class="modal-footer left">
				<button type="submit" class="btn btn-small btn-primary">Send Password</button>
			</form>
        <button class="btn btn-small btn-primary" data-dismiss="modal">Close Window</button>
			</div>
							<div class="clearfix"></div>
		</div>
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
						<div class="clearfix"></div>
</template>
<!-- END: PASSWORD RESET MODAL -->

<!-- BEGIN: ABOUT CHARISMA MODAL -->
<template id="about_charisma_modal">

		<div class="modal hide fade" id="aboutCharisma">
			<div class="modal-header">
				<h3><i class="icon-info-sign"></i> About Charisma</h3>
			</div>
			<div class="modal-body">
				<p><a href="http://usman.it/free-responsive-admin-template">Charisma</a> is a fully featured, free, premium quality, responsive, HTML5 admin template<br />(or backend template) based on Bootstrap from Twitter.</p>
			</div>
			<div class="modal-footer">
			<p class="pull-left">&copy; <a href="http://usman.it/">Muhammad Usman</a> 2012</p>
			<p class="pull-right"><a href="#" class="btn" data-dismiss="modal">Close</a></p>
			</div>
		</div>
</template>
<!-- END: ABOUT CHARISMA MODAL -->