<?php
/**
 * The main file
 *
 * @file        $Source: /README.md  $
 * @package     core
 * @author      ZE3kr <ze3kr@icloud.com>
 *
 */
$starttime = microtime(true);

require_once "settings.php";
require_once 'cloudflare.class.php';

$tlo_id = false;

if (!isset($_COOKIE['user_key']) || !isset($_COOKIE['cloudflare_email']) || !isset($_COOKIE['user_api_key'])) {
	$_GET['action'] = 'login';
	if (isset($_POST['cloudflare_email']) && isset($_POST['cloudflare_pass'])) {
		$cloudflare_email = $_POST['cloudflare_email'];
		$cloudflare_pass = $_POST['cloudflare_pass'];
		$cloudflare = new CloudFlare;
		$res = $cloudflare->userCreate($cloudflare_email, $cloudflare_pass);
		$times = apcu_fetch('login_' . date("Y-m-d H") . $cloudflare_email);
		if ($times > 5) {
			$msg = '<p>' . _('You have been blocked since you have too many fail logins. You can try it in next hour.') . '</p>';
		} elseif ($res['result'] == 'success') {
			if (isset($_POST['remember'])) {
				$cookie_time = time() + 31536000; // Expired in 365 days.
			} else {
				$cookie_time = 0;
			}
			setcookie('cloudflare_email', $res['response']['cloudflare_email'], $cookie_time);
			setcookie('user_key', $res['response']['user_key'], $cookie_time);
			setcookie('user_api_key', $res['response']['user_api_key'], $cookie_time);

			header('location: ./' . $_SERVER['QUERY_STRING']);
		} else {
			$times = $times + 1;
			apcu_store('login_' . date("Y-m-d H") . $cloudflare_email, $times, 7200);
			$msg = $res['msg'];
		}
	}
} else {
	$key = new \Cloudflare\API\Auth\APIKey($_COOKIE['cloudflare_email'], $_COOKIE['user_api_key']);
	$adapter = new Cloudflare\API\Adapter\Guzzle($key);
	$tlo_id = md5($_COOKIE['cloudflare_email'] . $_COOKIE['user_api_key']);
}

h2push('css/bootstrap.min.css', 'style');
h2push('css/tlo_v2.css', 'style');
h2push('js/jquery-3.3.1.slim.min.js', 'script');
h2push('js/bootstrap.bundle.min.js', 'script');
?><!DOCTYPE html>
<html class="no-js" lang="<?php echo $iso_language; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="TlOxygen Cloudflare Partners">
	<meta name="keywords" content="TlOxygen, Cloudflare">
	<title><?php if (isset($_GET['action']) && isset($action_name[$_GET['action']])) {echo $action_name[$_GET['action']];} else {echo _('Console');}?> | <?php echo _('Cloudflare CNAME/IP Advanced Setup'); ?> &#8211; <?php echo $page_title; ?></title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/tlo_v2.css">
</head>
<body class="bg-light">
	<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
		<a class="navbar-brand" href="./"><?php echo $page_title; ?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active nav-link">
					<?php if (isset($_GET['action']) && isset($action_name[$_GET['action']])) {echo $action_name[$_GET['action']];} else {echo _('Console');}?> <span class="sr-only">(current)</span>
				</li>
				<?php if (!isset($_GET['action']) || $_GET['action'] != 'login' && $_GET['action'] != 'logout') {?>
				<li class="nav-item float-md-right">
					<a class="nav-link" href="?action=logout"><?php echo _('Logout'); ?></a>
				</li>
				<?php }?>
			</ul>
		</div>
	</nav>
	<main class="bg-white">
<?php
$cloudflare = new CloudFlare;
if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = false;
}

switch ($action) {
case 'logout':
	require_once 'actions/logout.php';
	break;
case 'dnssec':
	require_once 'actions/dnssec.php';
	break;
case 'add_record':
	require_once 'actions/add_record.php';
	break;
case 'edit_record':
	require_once 'actions/edit_record.php';
	break;
case 'delete_record':
	require_once 'actions/delete_record.php';
	break;
case 'analytics':
	require_once 'actions/analytics.php';
	break;
case 'add':
	require_once 'actions/add.php';
	break;
case 'zone':
	require_once 'actions/zone.php';
	break;
case 'security':
	require_once 'actions/security.php';
	break;
case 'login':
	require_once 'actions/login.php';
	break;
default:
	require_once 'actions/list_zones.php';
	break;
}
?>
<hr>
<div class="am-container">
<p><a href="https://support.cloudflare.com/hc" target="_blank"><?php echo _('Cloudflare Support'); ?></a></p>
<?php
if (isset($is_beta) && $is_beta) {
	$time = round(microtime(true) - $starttime, 3);
	echo '<small><p>Beta Version / Load time: ' . $time . 's </p></small>';
}
?>
</div>
<hr>
<div class="am-container">
	<?php if ($is_beta) {echo '<p>' . _('Last Update: ') . date('Y-m-d H:i:s e', filemtime(__FILE__)) . '</p>';}?>
	<p><a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup" target="_blank"><?php echo _('View on GitHub'); ?></a></p>
</div>
	</main>
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
	</script>
</body>
</html>
