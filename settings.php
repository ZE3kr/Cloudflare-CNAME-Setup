<?php

if (@file_exists("../config.php")) {
	require_once "../config.php";
} else {
	require_once "config.php";
}

if (!defined('HOST_KEY') || !defined('HOST_MAIL')) {
	exit(_('No HOST_KEY or HOST_MAIL defined in config.php .'));
} elseif (HOST_KEY === 'e9e4498f0584b7098692512db0c62b48' || HOST_MAIL === 'ze3kr@example.com') {
	exit(_('Please set up your own HOST_KEY and HOST_MAIL in config.php .'));
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

if ($is_debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/includes/netdns2-1.4/DNS2.php';
require_once dirname(__FILE__) . '/includes/PHPMailer-6.0/src/Exception.php';
require_once dirname(__FILE__) . '/includes/PHPMailer-6.0/src/PHPMailer.php';
require_once dirname(__FILE__) . '/includes/PHPMailer-6.0/src/SMTP.php';
