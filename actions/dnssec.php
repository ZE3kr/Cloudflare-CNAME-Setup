<?php
/*
 * Enable or disable DNSSEC.
 */

if(!isset($tlo_id)){ exit; }

$dnssec = $adapter->patch('zones/' . $_GET['zoneid'] . '/dnssec', [], ['status' => $_GET['do']]);
$dnssec = json_decode($dnssec->getBody());
if ( $dnssec->success ) {
	$msg = '<p>'._('Success').', <a href="?action=zones&domain='.$_GET['domain'].'&amp;zoneid='.$_GET['zoneid'].'">'._('Go to console').'</a></p>';
} else {
	$msg = '<p>'._('Failed').', <a href="?action=zones&domain='.$_GET['domain'].'&amp;zoneid='.$_GET['zoneid'].'">'._('Go to console').'</a></p>';
}
echo $msg;
