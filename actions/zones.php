<?php
/*
 * Zone setup page
 */
if(!isset($tlo_id)){ exit; }

$zone_name = $_GET['domain'];
if(!isset($_GET['page'])){
	$_GET['page'] = 1;
}
$dns = new Cloudflare\API\Endpoints\DNS($adapter);
$zones = new Cloudflare\API\Endpoints\Zones($adapter);

$zoneID = $_GET['zoneid'];

$dnsresult_data = $dns->listRecords($zoneID, false, false, false, intval($_GET['page']));

$dnsresult = $dnsresult_data->result;

foreach ($dnsresult as $record) {
	$dnsids[$record->id] = true;
	$dnsproxyied[$record->id] = $record->proxied;
	$dnstype[$record->id] = $record->type;
	$dnscontent[$record->id] = $record->content;
	$dnsname[$record->id] = $record->name;
	$dnscheck[$record->name] = true;
}

/*
 * We need `_tlo-wildcard` subdomain to support anycast IP information.
 */
if(!isset($dnscheck['_tlo-wildcard.'.$zone_name]) && $_GET['page'] == 1){
	try {
		$dns->addRecord($zoneID, 'CNAME', '_tlo-wildcard', 'cloudflare.tlo.xyz');
	} catch (Exception $e) {
		echo '';
	}
}

?>
<strong><?php echo '<a href="?action=zones&amp;domain='.$zone_name.'&amp;zoneid='.$zoneID.'">'.strtoupper($zone_name).'</a>'; ?></strong><hr>
<div class="am-scrollable-horizontal"><?php
if(isset($_GET['enable']) && !$dnsproxyied[$_GET['enable']]){
	if($dns->updateRecordDetails($zoneID,$_GET['enable'],['type' => $dnstype[$_GET['enable']],'content' => $dnscontent[$_GET['enable']],'name' => $dnsname[$_GET['enable']],'proxied' => true])->success == true){
		echo '<p style="color:green;">'._('Success').'! </p>';
	} else {
		echo '<p style="color:red;">'._('Failed').'! </p><p><a href="?action=zones&amp;domain='.$zone_name.'&amp;zoneid='.$zoneID.'">'._('Go to console').'</a></p>';
		exit();
	}
} else {
	$_GET['enable'] = 1;
	if(isset($_GET['disable']) && $dnsproxyied[$_GET['disable']]){
		if($dns->updateRecordDetails($zoneID,$_GET['disable'],['type' => $dnstype[$_GET['disable']],'content' => $dnscontent[$_GET['disable']],'name' => $dnsname[$_GET['disable']],'proxied' => false])->success == true){
			echo '<p style="color:green;">'._('Success!').'</p>';
		} else {
			echo '<p style="color:red;">'._('Failed').'! </p><p><a href="?action=zones&amp;domain='.$zone_name.'&amp;zoneid='.$zoneID.'">'._('Go to console').'</a></p>';
			exit();
		}
	} else {
		$_GET['disable'] = 1;
	}
}
?>
	<ul>
		<li><a href="#dns"><?php echo _('DNS Management'); ?></a></li>
		<li><a href="#cname"><?php echo _('CNAME Setup'); ?></a></li>
		<li><a href="#ip"><?php echo _('IP Setup'); ?></a></li>
		<li><a href="#ns"><?php echo _('NS Setup'); ?></a></li>
		<li><a href="https://dash.cloudflare.com/" target="_blank"><?php echo _('More Settings'); ?></a></li>
	</ul>
	<h1 id="dns"><?php echo _('DNS Management'); ?> / <small><a href='?action=add_record&amp;zoneid=<?php echo $zoneID; ?>&amp;domain=<?php echo $zone_name; ?>'><?php echo _('Add New Record'); ?></a></small></h1>
<table class="am-table am-table-striped am-table-hover am-table-striped am-text-nowrap">
	<thead>
		<tr>
			<th><?php echo _('Host Name'); ?></th>
			<th><?php echo _('Record Type'); ?></th>
			<th><?php echo _('Content'); ?></th>
			<th><?php echo _('TTL'); ?></th>
			<th><?php echo _('Operation'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$no_record_yet = true;
		foreach ($dnsresult as $record) {
			if($record->name != '_tlo-wildcard.'.$zone_name){
				if($record->proxiable){
					if($record->proxied){
						$proxiable = '<a href="?action=zones&domain='.$zone_name.'&disable='.$record->id.'&page='.$_GET['page'].'&amp;zoneid='.$zoneID.'"><img src="https://cdn.landcement.com/uploads/2017/11/cloud_on.png" height="19"></a>';
					} else {
						$proxiable = '<a href="?action=zones&domain='.$zone_name.'&enable='.$record->id.'&page='.$_GET['page'].'&amp;zoneid='.$zoneID.'"><img src="https://cdn.landcement.com/uploads/2017/11/cloud_off.png" height="30"></a>';
					}
				} else {
					$proxiable = _('Not support CDN');
				}
				if(isset($_GET['enable']) && $record->id == $_GET['enable']){
					$proxiable = '<a href="?action=zones&domain='.$zone_name.'&disable='.$record->id.'&page='.$_GET['page'].'&amp;zoneid='.$zoneID.'"><img src="https://cdn.landcement.com/uploads/2017/11/cloud_on.png" height="19"></a>';
				} elseif(isset($_GET['disable']) && $record->id == $_GET['disable']) {
					$proxiable = '<a href="?action=zones&domain='.$zone_name.'&enable='.$record->id.'&page='.$_GET['page'].'&amp;zoneid='.$zoneID.'"><img src="https://cdn.landcement.com/uploads/2017/11/cloud_off.png" height="30"></a>';
				}
				if($record->type == 'MX'){
					$priority = '<code>'.$record->priority.'</code> ';
				} else {
					$priority = '';
				}
				if(isset($ttl_translate[$record->ttl])){
					$ttl = $ttl_translate[$record->ttl];
				} else {
					$ttl = $record->ttl.' s';
				}
				$no_record_yet = false;
				echo '<tr>
				<td><code>'.$record->name.'</code></td>
				<td><code>'.$record->type.'</code></td>
				<td>'.$priority.'<code>'.$record->content.'</code></td>
				<td>'.$ttl.'</td>
				<td>'.$proxiable.' | <a href="?action=edit_record&domain='.$zone_name.'&recordid='.$record->id.'&zoneid='.$zoneID.'">'._('Edit').'</a> | <a href="?action=delete_record&domain='.$zone_name.'&delete='.$record->id.'&zoneid='.$zoneID.'" onclick="return confirm(\''._('Are you sure to delete').' '.$record->name.'?\')">'._('Delete').'</a></td></tr>';
			}
		}
		?>
	</tbody>
</table><?php

if($no_record_yet){
	echo '<h2 style="text-align: center;color:red;">'._('There is no record in this zone yet. Please add some!').'</h2>';
}

if(isset($dnsresult_data->result_info->total_pages)){
	$previous_page = '';
	$next_page = '';
	if($dnsresult_data->result_info->page < $dnsresult_data->result_info->total_pages){
		$page_link = $dnsresult_data->result_info->page + 1;
		$next_page = ' | <a href="?action=zones&domain='.$zone_name.'&page='.$page_link.'&amp;zoneid='.$zoneID.'">'._('Next').'</a>';
	}
	if($dnsresult_data->result_info->page > 1){
		$page_link = $dnsresult_data->result_info->page - 1;
		$previous_page = '<a href="?action=zones&domain='.$zone_name.'&page='.$page_link.'&amp;zoneid='.$zoneID.'">'._('Previous').'</a> | ';
	}
	echo '<p>'.$previous_page._('Page').' '.$dnsresult_data->result_info->page.'/'.$dnsresult_data->result_info->total_pages.$next_page.'</p>';
}
?>
<p><?php echo _('You can use CNAME, IP or NS to set it up.'); ?></p>
<h1 id="cname"><?php echo _('CNAME Setup'); ?></h1>
<table class="am-table am-table-striped am-table-hover am-table-striped am-text-nowrap">
	<thead>
		<tr>
			<th><?php echo _('Host Name'); ?></th>
			<th>CNAME</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$resolver = new Net_DNS2_Resolver( array('nameservers' => array('162.159.2.9','162.159.9.55')) );
		$avoid_cname_duplicated = [];
		foreach ($dnsresult as $record) {
			if($record->name != '_tlo-wildcard.'.$zone_name && !isset($avoid_cname_duplicated[$record->name])){
				echo '<tr>
				<td><code>'.$record->name.'</code></td>
				<td><code>'.$record->name.'.cdn.cloudflare.net</code></td>
				</tr>';
				$avoid_cname_duplicated[$record->name] = true;
			}
		}
		?>
	</tbody>
</table><?php

if($no_record_yet){
	echo '<h2 style="text-align: center;color:red;">'._('There is no record in this zone yet. Please add some!').'</h2>';
}

if(isset($dnsresult_data->result_info->total_pages)){
	$previous_page = '';
	$next_page = '';
	if($dnsresult_data->result_info->page < $dnsresult_data->result_info->total_pages){
		$page_link = $dnsresult_data->result_info->page + 1;
		$next_page = ' | <a href="?action=zones&domain='.$zone_name.'&page='.$page_link.'">'._('Next').'</a>';
	}
	if($dnsresult_data->result_info->page > 1){
		$page_link = $dnsresult_data->result_info->page - 1;
		$previous_page = '<a href="?action=zones&domain='.$zone_name.'&page='.$page_link.'">'._('Previous').'</a> | ';
	}
	echo '<p>'.$previous_page._('Page').' '.$dnsresult_data->result_info->page.'/'.$dnsresult_data->result_info->total_pages.$next_page.'</p>';
}
try {
	$resp_a = $resolver->query('_tlo-wildcard.' . $zone_name, 'A');
	$resp_aaaa = $resolver->query('_tlo-wildcard.'.$zone_name, 'AAAA');
	$resp = $resolver->query($zone_name, 'NS');
} catch (Net_DNS2_Exception $e) {
	echo $e->getMessage();
}
?>
<h1 id="ip"><?php echo _('IP Setup'); ?></h1>
<h2>Anycast IPv4</h2>
<ul>
	<li><code><?php echo $resp_a->answer[0]->address; ?></code></li>
	<li><code><?php echo $resp_a->answer[1]->address; ?></code></li>
</ul>
<h2>Anycast IPv6</h2>
<ul>
	<li><code><?php echo $resp_aaaa->answer[0]->address; ?></code></li>
	<li><code><?php echo $resp_aaaa->answer[1]->address; ?></code></li>
</ul>
<h1 id="ns"><?php echo _('NS Setup'); ?></h1>
<table class="am-table am-table-striped am-table-hover am-table-striped am-text-nowrap">
	<thead>
		<tr>
			<th><?php echo _('Host Name'); ?></th>
			<th>NS</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $zone_name; ?></td>
			<td><?php echo $resp->answer[0]->nsdname; ?></td>
			</tr><tr>
			<td><?php echo $zone_name; ?></td>
			<td><?php echo $resp->answer[1]->nsdname; ?></td>
			</tr></tbody>
</table>
<h1><a href="https://dash.cloudflare.com/" target="_blank"><?php echo _('More Settings'); ?></a></h1>
<p><?php echo _('This site only provides configurations that the official does not have. For more settings, such as Page Rules, Crypto, Firewall, Cache, etc., please use the same account to login Cloudflare.com to setup. '); ?><a href='https://www.cloudflare.com/a/overview/<?php echo $zone_name; ?>' target="_blank"><?php echo _('More Settings'); ?></a></p>
</div>
