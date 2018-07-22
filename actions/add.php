<?php
/*
 * Using Partner API to add domain for CNAME or NS setup.
 */

if(!isset($tlo_id)){ exit; }

$msg = '';
if(isset($_POST['submit']))
{
	$zone_name = $_POST['domain'];
	if(isset($_POST['type']) && $_POST['type'] == 'ns') {
		/* NS setup */
		$res = $cloudflare->zoneSet_full($zone_name);
		if ( $res['result'] == 'success' ) {
			$msg = _('Success').', <a target="_blank" href="https://www.cloudflare.com/a/overview/'.$zone_name.'">'._('Go to console').'</a>. ';
			exit($msg);
		} else {
			if (isset($res['msg'])) {
				exit($res['msg']);
			} else {
				print_r($res);
				exit;
			}
		}
	}
	/*
	 * We need `_tlo-wildcard` subdomain to support anycast IP information.
	 */
	$res = $cloudflare->zoneSet($zone_name,'example.com','_tlo-wildcard');
	if ( $res['result'] == 'success' ) {
		$zones = new \Cloudflare\API\Endpoints\Zones($adapter);
		try {
			$zoneID = $zones->getZoneID($zone_name);
		} catch (Exception $e){
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

		$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
		$dnsresult = $dns->listRecords($zoneID)->result;
		/*
		 * Delete @ and `www` record to make this zone fresh.
		 */
		foreach ($dnsresult as $record) {
			if($record->name == $zone_name){
				$dns->deleteRecord($zoneID, $record->id);
			} elseif($record->name == 'www.'.$zone_name) {
				$dns->deleteRecord($zoneID, $record->id);
			}
		}
		$msg = _('Success').', <a href="?action=zones&amp;domain='.$zone_name.'&amp;zoneid='.$zoneID.'">'._('Go to console').'</a>. ';
		exit($msg);
	} else {
		$msg = $res['msg'];
	}
}
echo $msg;
?>
<form method="POST" action="" class="am-form am-form-horizontal">
	<div class="am-form-group">
		<label for="doc-ipt-3" class="am-u-sm-2 am-form-label"><?php echo _('Domain'); ?></label>
		<div class="am-u-sm-10">
			<input type="text" id="doc-ipt-3" name="domain" placeholder="<?php echo _('Please enter your domain'); ?>">
		</div>
	</div>
	<div class="am-form-group">
		<div class="am-u-sm-10 am-u-sm-offset-2">
			<button type="submit" name="submit" class="am-btn am-btn-default"><?php echo _('Submit'); ?></button>
		</div>
	</div>
</form>
