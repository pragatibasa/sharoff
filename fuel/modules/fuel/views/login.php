<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="https://apis.google.com/js/platform.js" async defer></script>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="google-signin-client_id" content="782419503683-mb5j95pou0rr0o5074bthjviubhigjf0.apps.googleusercontent.com">
	<link rel="shortcut icon" href="<?php echo img_path('../../../../../assets/images/favicon.ico');?>" type="image/x-icon"/>
		<title><?=$page_title?></title>
	<?=css('login', FUEL_FOLDER)?>
	<script type="text/javascript">
	<?=$this->load->module_view('fuel', '_blocks/fuel_header_jqx', array(), true)?>
	</script>
	<?=js('jquery/jquery', FUEL_FOLDER)?>
	<?=js('jqx/jqx', FUEL_FOLDER)?>
	<script type="text/javascript">
		jqx.addPreload('fuel.controller.BaseFuelController');
		jqx.init('fuel.controller.LoginController', {});
	</script>
</head>
<body>
		<div id="header_login">
		<center><img src="<?=img_path('SST LOGO.png')?>" width="250" height="100" alt="FUEL CMS" border="0" id="login_logo" /></center>
		</div>
<div id="login">
	<div id="login_inner">
		<?php if (!empty($instructions)) : ?>
		<p><?=$instructions?></p>
		<?php endif; ?>
		<?=$form?>
	</div>	
	<div id="login_notification" class="notification">
		<!-- <div class="g-signin2" data-onsuccess="onSignIn"></div> -->
			<?=$notifications?>
	</div>

	<div id="settingdiv" style="padding-left:17px;">
		<div>
		<?php if ($display_forgotten_pwd) : ?>
			<a href="<?=fuel_url('login/pwd_reset')?>" id="forgotten_pwd"><?=lang('login_forgot_pwd')?></a>
		<?php endif; ?>
		</div>
		<div>
		<?php if ($display_forgotten_pwd) : ?>
			Don't have an account, <a href="<?=fuel_url('#')?>">Sign Up</a>
		<?php endif; ?>
		</div>
	</div>
	<div id="login_footer">
		<div align="center">Copyright &copy; 2016 <a href="http://www.aspensteel.in" target="_blank">Aspen Steel</a>. All Rights Reserved.</div>
	</div>
</div>
</body>
</html>
