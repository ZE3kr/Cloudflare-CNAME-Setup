<?php
/*
 * Delete a specific record for a domain.
 */

if (!isset($adapter)) {exit;}

$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
try {
	if ($dns->deleteRecord($_GET['zoneid'], $_GET['delete'])) {
		echo '<p class="alert alert-success" role="alert">' . _('Success') . '! </p><p><a href="?action=zone&domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>';
	} else {
		echo '<p class="alert alert-danger" role="alert">' . _('Failed') . '! </p><p><a href="?action=zone&domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>';
	}
} catch (Exception $e) {
	exit('<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
}
