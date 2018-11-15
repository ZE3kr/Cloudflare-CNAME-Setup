<?php
/*
 * Enable or disable DNSSEC.
 */

if (!isset($adapter)) {exit;}

try {
	$dnssec = $adapter->patch('zones/' . $_GET['zoneid'] . '/dnssec', ['status' => $_GET['do']]);
	$dnssec = json_decode($dnssec->getBody());
} catch (Exception $e) {
	exit('<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
}

if ($dnssec->success) {
	$msg = '<p class="alert alert-success" role="alert">' . _('Success') . ', <a href="?action=security&domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>';
} else {
	$msg = '<p class="alert alert-danger" role="alert">' . _('Failed') . ', <a href="?action=security&domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>';
}
echo $msg;
