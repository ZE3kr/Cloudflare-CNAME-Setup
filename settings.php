<?php

if (file_exists('config.php')) {
	require_once 'config.php';
} else if (@file_exists('../config.php')) {
	require_once '../config.php';
}

if (!defined('HOST_KEY') || !defined('HOST_MAIL') || HOST_KEY === 'e9e4498f0584b7098692512db0c62b48' ||
    HOST_MAIL === 'ze3kr@example.com') {
    $no_api_key = true;
} else {
    $no_api_key = false;
}

if (!isset($page_title)) {
	$page_title = "TlOxygen";
}

/*
 * A quick fix for the server that does not support APCu Cache.
 */
if (!function_exists('apcu_fetch')) {
	function apcu_fetch() {
		return false;
	}
	function apcu_store() {
		return false;
	}
}

$language_supported = [
	'zh' => 'zh_CN.UTF-8',
];
$lan = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
$lan = strtolower($lan);
$short_lan = substr($lan, 0, 2);
$dir = __DIR__ . '/languages';
$domain = 'messages';
if (isset($language_supported[$short_lan])) {
	$locale = $language_supported[$short_lan];
	$iso_language = $short_lan;
} else {
	$locale = 'en';
	$iso_language = 'en';
}
putenv('LANG=' . $locale);
setlocale(LC_MESSAGES, $locale);
bindtextdomain($domain, $dir);
bind_textdomain_codeset($domain, "UTF-8");
textdomain($domain);
require_once 'languages/translates.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Content-Type: text/html; charset=UTF-8");

if (isset($is_debug) && $is_debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
	require_once dirname(__FILE__) . '/vendor/autoload.php';
} else { ?>
	<html <?php if (isset($iso_language)) {echo 'lang="' . $iso_language . '"';}?>>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="TlOxygen Cloudflare Partners">
		<meta name="keywords" content="TlOxygen, Cloudflare">
		<title><?php
			echo _('Error') . ' | ' . _('Cloudflare CNAME/IP Advanced Setup') . ' &#8211; ' . $page_title;
		?></title>
		<meta name="renderer" content="webkit">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/tlo.css?ver=<?php echo urlencode($version) ?>">
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
					<?php echo _('Error'); ?> <span class="sr-only">(current)</span>
				</li>
			</ul>
		</div>
	</nav>
	<main class="bg-white">
		<div class="alert alert-danger" role="alert">
			<?php echo _('Cannot find the file').': <code>'.dirname(__FILE__). '/vendor/autoload.php</code>';?>
		</div>
		<?php
		echo '</p><p>'._('It probably means that <code>composer</code> dependencies are not installed properly.').'</p>';
		echo '<p>'._('You will need to run the following codes to install the dependencies:').'</p>'.
			'<pre class="alert alert-dark">cd '.dirname(__FILE__)."\n".
			'composer install --no-dev -o</pre>';
		echo '<p>'._('If you do not have the <code>composer</code>, install it first (you may need to run it with <code>sudo</code>):').'</p>'.
			'<pre class="alert alert-dark">curl -sS https://getcomposer.org/installer | php'."\n".
			'mv composer.phar /usr/local/bin/composer</pre>';
		?>

	</main>
	<footer class="footer">
		<p><a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup" target="_blank"><?php echo _('View on GitHub'); ?></a></p>
	</footer>
	</body>
	</html>
	<?php exit();
}
