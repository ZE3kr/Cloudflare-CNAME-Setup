<?php
/*
 * Security page. (SSL and DNSSEC information)
 */

if (!isset($adapter)) {exit;}

$zone_name = $_GET['domain'];
if (!isset($_GET['page'])) {
	$_GET['page'] = 1;
}
$dns = new Cloudflare\API\Endpoints\DNS($adapter);
$zones = new Cloudflare\API\Endpoints\Zones($adapter);

$zoneID = $_GET['zoneid'];

?>
<strong><?php echo '<h1 class="h5"><a href="?action=security&amp;domain=' . $zone_name . '&amp;zoneid=' . $zoneID . '">' . strtoupper($zone_name) . '</a></h1>'; ?></strong>
<hr>
<div class="am-scrollable-horizontal">
	<h3 id="ssl" class="mt-5 mb-3"><?php echo _('SSL Verify'); ?></h3><?php
try {
	$sslverify = $adapter->get('zones/' . $zoneID . '/ssl/verification?retry=true');
	$sslverify = json_decode($sslverify->getBody(), true)['result'];
} catch (Exception $e) {
	$sslverify[0]['validation_method'] = 'http';
}

foreach ($sslverify as $sslv) {
/*
 * We need `_tlo-wildcard` subdomain to support anycast IP information.
 */
	if (substr($sslv['hostname'], 0, 14) != '_tlo-wildcard.') {
		if ($sslv['validation_method'] == 'http' && isset($sslv['verification_info']['http_url']) && $sslv['verification_info']['http_url'] != '') {?>
			<h4><?php printf(_('HTTP File Verify for %s'), $sslv['hostname']);?></h4>
			<p>URL: <code><?php echo $sslv['verification_info']['http_url']; ?></code></p>
			<p>Body: <code><?php echo $sslv['verification_info']['http_body']; ?></code></p><?php
if ($sslv['certificate_status'] != 'active') {
			echo '<p>' . _('SSL Status') . ': ' . $sslv['certificate_status'] . '</p>';
			if ($sslv['verification_status']) {
				echo '<p>' . _('Verify') . ': <span style="color:green;">' . _('Success') . '</span></p>';
			} else {
				echo '<p>' . _('Verify') . ': <span style="color:red;">' . _('Failed') . '</span></p>';
			}
		}
		} elseif ($sslv['validation_method'] == 'cname' || isset($sslv['verification_info']['record_name'])) {?>
			<h4><?php echo _('CNAME Verify'); ?></h4>
			<table class="am-table am-table-striped am-table-hover am-table-striped am-text-nowrap">
			<thead>
			<tr>
				<th><?php echo _('SSL Verification Record Name'); ?></th>
				<th>CNAME</th>
			</tr>
			</thead>
			<tbody>

			<?php echo '<tr>
				<td><code>' . $sslv['verification_info']['record_name'] . '</code></td>
				<td><code>' . $sslv['verification_info']['record_target'] . '</code></td>
				</tr>';
			?>
			</tbody>
			</table><?php
if ($sslv['certificate_status'] != 'active') {
				echo '<p>' . _('SSL Status') . ': ' . $sslv['certificate_status'] . '</p>';
				if ($sslv['verification_status']) {
					echo '<p>' . _('Verify') . ': <span style="color:green;">' . _('Success') . '</span></p>';
				} else {
					echo '<p>' . _('Verify') . ': <span style="color:red;">' . _('Failed') . '</span></p>';
				}
			}
		} elseif ($sslv['validation_method'] == 'http') {
			if (isset($sslv['hostname'])) {echo '<h4>' . $sslv['hostname'] . '</h4>';}
			echo _('<p style="color:green;">No error for SSL.</p><p>Just point the record(s) to Cloudflare and the SSL certificate will be issued and renewed automatically.</p>');
		} else {
			echo '<h4>Unknown Verification</h4><pre>';
			print_r($sslv['verification_info']);
			echo '</pre>';
			if ($sslv['certificate_status'] != 'active') {
				echo '<p>' . _('SSL Status') . ': ' . $sslv['certificate_status'] . '</p>';
				if ($sslv['verification_status']) {
					echo '<p>' . _('Verify') . ': <span style="color:green;">' . _('Success') . '</span></p>';
				} else {
					echo '<p>' . _('Verify') . ': <span style="color:red;">' . _('Failed') . '</span></p>';
				}
			}
		}
	}
}
?>
	<h3 class="mt-5 mb-3"><?php echo _('DNSSEC <small>(Only for NS setup)</small>'); ?></h3><?php

echo '<p>' . _('This feature is designed for users who use Cloudflare DNS setup. If you are using third-party DNS services, do not turn it on nor add DS record, otherwise your domain may become inaccessible.') . '</p>';

try {
	$dnssec = $adapter->get('zones/' . $zoneID . '/dnssec');
	$dnssec = json_decode($dnssec->getBody());
} catch (Exception $e) {
	exit('<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
}

if ($dnssec->result->status == 'active') {
	echo '<p style="color:green;">' . _('Activated') . '</p><p>DS：<code>' . $dnssec->result->ds . '</code></p><p>Public Key：<code>' . $dnssec->result->public_key . '</code></p>';
	echo '<p><a href="?action=dnssec&zoneid=' . $zoneID . '&domain=' . $zone_name . '&do=disabled">' . _('Deactivate') . '</a></p>';
} elseif ($dnssec->result->status == 'pending') {
	echo '<p style="color:orange;">' . _('Pending') . '</p><p>DS：<code>' . $dnssec->result->ds . '</code></p><p>Public Key：<code>' . $dnssec->result->public_key . '</code></p>';
	echo '<p><a href="?action=dnssec&zoneid=' . $zoneID . '&domain=' . $zone_name . '&do=disabled">' . _('Deactivate') . '</a></p>';
} else {
	echo '<p style="color:red;">' . _('Not Activated') . '</p>';
	echo '<p><a href="?action=dnssec&zoneid=' . $zoneID . '&domain=' . $zone_name . '&do=active" onclick="return confirm(\'' . _('This feature is designed for users who use Cloudflare DNS setup. If you are using third-party DNS services, do not turn it on nor add DS record, otherwise your domain may become inaccessible.') . '\')">' . _('Activate') . '</a></p>';
} ?>
</div>
