<?php
/**
 * The main file
 *
 * @file    $Source: /README.md  $
 * @package core
 * @author  ZE3kr <ze3kr@icloud.com>
 *
 */

$starttime = microtime(true);
$page_title = '';
$version = '1.2.3';

require_once 'settings.php';
require_once 'cloudflare.class.php';

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

			header('location: ./');
		} else {
			$times = $times + 1;
			apcu_store('login_' . date("Y-m-d H") . $cloudflare_email, $times, 7200);
			$msg = $res['msg'];
		}
	}
} else {
	$key = new \Cloudflare\API\Auth\APIKey($_COOKIE['cloudflare_email'], $_COOKIE['user_api_key']);
	$adapter = new Cloudflare\API\Adapter\Guzzle($key);
}
if (!isset($_COOKIE['tlo_cached_main'])) {
	h2push('css/bootstrap.min.css', 'style');
	h2push('css/tlo.css?ver=' . urlencode($version), 'style');
	h2push('js/jquery-3.3.1.slim.min.js', 'script');
	h2push('js/bootstrap.bundle.min.js', 'script');
	h2push('js/main.js?ver=' . urlencode($version), 'script');
	setcookie('tlo_cached_main', 1);
}

if (isset($_GET['action']) && $_GET['action'] == 'zone' && !isset($_COOKIE['tlo_cached_cloud'])) {
	h2push('images/cloud_on.png', 'script');
	h2push('images/cloud_off.png', 'script');
	setcookie('tlo_cached_cloud', 1);
}
?><!DOCTYPE html>
<html <?php if (isset($iso_language)) {echo 'lang="' . $iso_language . '"';}?>>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php
if (isset($_GET['action']) && $_GET['action'] == 'analytics') {
	echo '<meta name="viewport" content="width=800">';
} else {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
}
?>
	<meta name="description" content="TlOxygen Cloudflare Partners">
	<meta name="keywords" content="TlOxygen, Cloudflare">
	<title><?php
if (isset($_GET['action'])) {
	if ($_GET['action'] != 'login') {
		if (isset($action_name[$_GET['action']])) {
			echo $action_name[$_GET['action']] . ' | ';
			if (isset($_GET['domain'])) {
				echo $_GET['domain'] . ' | ';
			}
		}
	} else {
		echo $action_name[$_GET['action']] . ' | ';
	}
} else {
	echo _('Console') . ' | ';
}

echo _('Cloudflare CNAME/IP Advanced Setup') . ' &#8211; ' . $page_title;
?></title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/tlo.css?ver=<?php echo urlencode($version) ?>">

	<link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
	<link rel="manifest" href="images/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
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
				<li class="nav-item">
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
	</main>
	<footer class="footer">
			<p><a href="https://support.cloudflare.com/hc" target="_blank"><?php echo _('Cloudflare Support'); ?></a></p>
			<p><a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup" target="_blank"><?php echo _('View on GitHub'); ?></a></p><?php
if ((isset($is_beta) && $is_beta) || (isset($is_debug) && $is_debug)) {
	$time = round(microtime(true) - $starttime, 3);
	echo '<small><p>Beta Version / Load time: ' . $time . 's </p>';
}
?>
	</footer>

	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/main.js?ver=<?php echo urlencode($version) ?>"></script>
</body>
</html>
