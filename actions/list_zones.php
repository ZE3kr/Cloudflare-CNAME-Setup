<?php
/*
 *  List zones. (Home page)
 */

if (!isset($adapter)) {exit;}

if (!isset($_GET['page'])) {
	$_GET['page'] = 1;
}
if(!$no_api_key){
?>
<a href="?action=add" class="btn btn-primary float-sm-right mb-3 d-block"><?php echo _('Add Domain'); ?></a>
<?php } ?>
<h3 class="d-none d-sm-block"><?php echo _('Home'); ?></h3>
<?php if($no_api_key){
	if(isset($tlo_promotion_header)){
		echo $tlo_promotion_header;
	} else {
		echo '<div class="alert alert-warning" role="alert">' . _('No Host API key found. You cannot add new domain to this service.') . '</div>';
	}
} ?>
<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col"><?php echo _('Domain'); ?></th>
		<th scope="col" class="d-none d-sm-table-cell"><?php echo _('Status'); ?></th>
		<th scope="col" class="d-none d-sm-table-cell"><?php echo _('Operation'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);
try {
	$zones_data = $zones->listZones(false, false, intval($_GET['page']));
} catch (Exception $e) {
	exit('<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
}

foreach ($zones_data->result as $zone) {
	echo '<tr>';
	$_translate_analytics = _('Advanced Analytics');
	$_translate_manage = _('Manage');
	$_translate_manage_dns = _('Manage DNS');
	$_translate_security = _('Security');
	if (property_exists($zone, 'name_servers')) {
		echo <<<HTML
		<td scope="col">
			<div class="dropleft d-inline float-right d-sm-none">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					{$_translate_manage}
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="https://dash.cloudflare.com/" target="_blank">{$_translate_manage_dns}</a>
				</div>
			</div>
			{$zone->name}
			<span class="d-block d-sm-none"> {$status_translate[$zone->status]}</span>
		</td>
HTML;
	} else {
		echo <<<HTML
		<td scope="col">
			<div class="dropleft d-inline float-right d-sm-none">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					{$_translate_manage}
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="?action=zone&amp;domain={$zone->name}&amp;zoneid={$zone->id}">{$_translate_manage_dns}</a>
					<a class="dropdown-item" href="?action=security&amp;domain={$zone->name}&amp;zoneid={$zone->id}">{$_translate_security}</a>
				</div>
			</div>
			{$zone->name}
			<span class="d-block d-sm-none"> {$status_translate[$zone->status]}</span>
			</div>
		</td>
HTML;

	}

	echo <<<HTML
		<td class="d-none d-sm-table-cell">{$status_translate[$zone->status]}</td>
		<td class="d-none d-sm-table-cell btn-group" role="group">
HTML;
	if (property_exists($zone, 'name_servers')) {
		echo '<a href="https://dash.cloudflare.com/" target="_blank" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="' . _('This domain only supports NS setup. And you should manage DNS records on Cloudflare.com.') . '">' . _('Manage DNS') . '</a>';
	} else {
		echo <<<HTML
<a href="?action=zone&amp;domain={$zone->name}&amp;zoneid={$zone->id}" class="btn btn-secondary btn-sm">{$_translate_manage_dns}</a>
HTML;
		echo <<<HTML
<a href="?action=security&amp;domain={$zone->name}&amp;zoneid={$zone->id}" class="btn btn-dark btn-sm">{$_translate_security}</a>
HTML;

	}
	echo '</td>';
}
?>
	</tbody>
</table>
<?php if($no_api_key && isset($tlo_promotion_footer)){
	echo $tlo_promotion_footer;
}
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
