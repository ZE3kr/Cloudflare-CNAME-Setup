<?php
/*
 * Delete a specific record for a domain.
 */

if(!isset($tlo_id)){ exit; }

$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
if($dns->deleteRecord($_GET['zoneid'],$_GET['delete']) ){
	echo '<p style="color:green;">'._('Success').'! </p><p><a href="?action=zones&domain='.$_GET['domain'].'&amp;zoneid='.$_GET['zoneid'].'">'._('Go to console').'</a></p>';
} else {
	echo '<p style="color:red;">'._('Failed').'! </p><p><a href="?action=zones&domain='.$_GET['domain'].'&amp;zoneid='.$_GET['zoneid'].'">'._('Go to console').'</a></p>';
}
