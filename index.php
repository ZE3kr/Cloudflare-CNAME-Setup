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

include "settings.php";

$tlo_id = false;

if (!isset($_COOKIE['user_key']) || !isset($_COOKIE['cloudflare_email']) || !isset($_COOKIE['user_api_key'])) {require_once "login.php";exit();}

$key = new \Cloudflare\API\Auth\APIKey($_COOKIE['cloudflare_email'], $_COOKIE['user_api_key']);
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
$tlo_id = md5($_COOKIE['cloudflare_email'] . $_COOKIE['user_api_key']);

?><!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="TlOxygen Cloudflare Partners">
	<meta name="keywords" content="TlOxygen, Cloudflare">
	<meta name="viewport" content="width=800">
	<title><?php if (isset($_GET['action']) && $_GET['action'] == 'analytics') {echo 'Analytics: ' . $_GET['domain'];} elseif (isset($_GET['domain'])) {echo 'DNS: ' . $_GET['domain'];} else {echo 'Console';}?> | <?php echo _('Cloudflare CNAME/IP Advanced Setup'); ?> &#8211; <?php echo $page_title; ?></title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp"/>
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/tlo.css">
</head>
<body>
	<div class="am-container">
		<div class="am-cf am-padding am-padding-bottom-0 data-am-sticky">
			<div class="am-fl am-cf">
				<strong class="am-text-primary am-text-lg"><a href="?"><?php echo $page_title; ?></a></strong> /
				<small><?php echo _('Console'); ?></small> /
				<small><a href="?action=logout"><?php echo _('Logout'); ?></a></small>
			</div>
		</div></div><hr>
		<div class="am-container" style=" max-width: 960px"><?php
require_once 'cloudflare.class.php';
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
case 'zones':
	require_once 'actions/zones.php';
	break;
case 'ssl':
	require_once 'actions/ssl.php';
	break;
default:
	if (!isset($_GET['page'])) {
		$_GET['page'] = 1;
	}
	?>
<a href="?action=add" class="am-btn am-btn-success am-round"><?php echo _('Add Domain'); ?></a>
<table class="am-table am-table-striped am-table-hover">
	<thead>
		<tr>
			<th><?php echo _('Domain'); ?></th>
			<th><?php echo _('Status'); ?></th>
			<th><?php echo _('Mode'); ?></th>
			<th><?php echo _('Operation'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);
	$zones_data = $zones->listZones(false, false, intval($_GET['page']));
	foreach ($zones_data->result as $zone) {
		if (property_exists($zone, 'name_servers')) {
			$manage_data = '<a href="https://dash.cloudflare.com/" target="_blank">';
			$manage_ssl = '';
			$cname_method = '<span style="color:orange;">' . _('Official Setup') . '</span>';
		} else {
			$manage_data = '<a href="?action=zones&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">';
			$manage_ssl = ' | <a href="?action=ssl&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">' . _('Security') . '</a>';
			$cname_method = '<span style="color:green;">' . _('Universal Setup') . '</span>';
		}
		echo '<tr>
		<td>' . $manage_data . $zone->name . '</a></td>
		<td>' . $status_translate[$zone->status] . '</td>
		<td>' . $cname_method . '</td>
		<td>' . $manage_data . _('Manage') . '</a>' . ' | <a href="?action=analytics&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">' . _('Advanced Analytics') . '</a>' . $manage_ssl . '</td>';
	}
	?>
	</tbody>
</table><?php
if (isset($zones_data->result_info->total_pages)) {
		$previous_page = '';
		$next_page = '';
		if ($zones_data->result_info->page < $zones_data->result_info->total_pages) {
			$page_link = $zones_data->result_info->page + 1;
			$next_page = ' | <a href="?page=' . $page_link . '">' . _('Next') . '</a>';
		}
		if ($zones_data->result_info->page > 1) {
			$page_link = $zones_data->result_info->page - 1;
			$previous_page = '<a href="?page=' . $page_link . '">' . _('Previous') . '</a> | ';
		}
		echo '<p>' . $previous_page . _('Page') . ' ' . $zones_data->result_info->page . '/' . $zones_data->result_info->total_pages . $next_page . '</p>';
	}
	break;
}
?>
</div>
<hr>
<div class="am-container">
<p><?php echo _('<a href="https://support.cloudflare.com/hc" target="_blank">Any questions or problems about Cloudflare, please contact official support</a></p><p>Any question or problem about this service, please <a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup/issues/new" target="_blank">create a issue on GitHub</a>'); ?></p><?php
if ($is_beta) {
	$time = round(microtime(true) - $starttime, 3);
	echo '<small><p>Beta Version / Load time: ' . $time . 's </p></small>';
}
?>
</div>
<hr>
<div class="am-container">
	<p><?php if ($is_beta) {echo _('Last Update: ') . date('Y-m-d H:i:s e', filemtime(__FILE__));}?></p>
	<p><a href="https://github.com/ZE3kr/Cloudflare-CNAME-Setup" target="_blank"><?php echo _('This open source project is powered by ZE3kr.'); ?></a></p>
</div>
</body>
</html>
