<?php
if(!isset($tlo_id)){ exit; } // This file should be included in index.php and cannot be accessed directly.
require_once 'cloudflare.class.php';
if (isset($_POST['submit'])) {
	$cloudflare_email = $_POST['cloudflare_email'];
	$cloudflare_pass = $_POST['cloudflare_pass'];
	$cloudflare = new CloudFlare;
	$res = $cloudflare->userCreate($cloudflare_email,$cloudflare_pass);
	$times = apcu_fetch('login_'.date("Y-m-d H").$cloudflare_email);
	if($times > 5){
		$msg = '<p>'._('You have been blocked since you have too many fail logins. You can try it in next hour.').'</p>';
	} elseif ($res['result'] == 'success') {
		setcookie('cloudflare_email',$res['response']['cloudflare_email']);
		setcookie('user_key',$res['response']['user_key']);
		setcookie('user_api_key',$res['response']['user_api_key']);
		header('location: ?'.$_SERVER['QUERY_STRING']);
	} else {
		$times = $times + 1;
		apcu_store('login_'.date("Y-m-d H").$cloudflare_email, $times, 7200);
		$msg = $res['msg'];
	}
}
?><!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="TlOxygen Cloudflare Partners">
	<meta name="keywords" content="TlOxygen, Cloudflare">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo _('Login'); ?> | <?php echo $page_title; ?></title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class="am-container">
		<div class="am-cf am-padding am-padding-bottom-0 data-am-sticky">
			<div class="am-fl am-cf">
				<strong class="am-text-primary am-text-lg"><a href="/"><?php echo $page_title; ?></a></strong> /
				<small>Login</small>
			</div>
		</div>
	</div><hr>
	<div class="am-container">
		<h1><?php echo _('Cloudflare CNAME/IP/NS Setup'); ?></h1>
		<p><?php echo _('Login'); ?></p>
		<form method="POST" action="" class="am-form am-form-horizontal">
			<div class="am-form-group">
				<label for="doc-ipt-3" class="am-u-sm-2 am-form-label"><?php echo _('Email'); ?></label>
				<div class="am-u-sm-10">
					<input type="email" id="doc-ipt-3" name="cloudflare_email" placeholder="<?php echo _('Your email address on cloudflare.com'); ?>">
				</div>
			</div>

			<div class="am-form-group">
				<label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label"><?php echo _('Password'); ?></label>
				<div class="am-u-sm-10">
					<input type="password" id="doc-ipt-pwd-2" name="cloudflare_pass" placeholder="<?php echo _('Your password address on cloudflare.com'); ?>">
				</div>
			</div>
			<p><?php echo _('Use your existing account or create a new account here.'); ?></p>

			<div class="am-form-group">
				<div class="am-u-sm-10 am-u-sm-offset-2">
					<button type="submit" name="submit" class="am-btn am-btn-default"><?php echo _('Login'); ?></button>
				</div>
			</div>
		</form>
		<p><?php echo _('We will not store any of your Cloudflare data'); ?></p>
		<?php if(isset($msg)) { echo $msg; } ?>
		<hr>
		<div class="am-container">
			<p><?php echo _('Last Update: ').date('Y-m-d H:i:s e', filemtime(__FILE__)); ?></p>
			<p><a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup" target="_blank"><?php echo _('This open source project is powered by ZE3kr.');?></a></p>
		</div>
	</div>
</body>
</html>
