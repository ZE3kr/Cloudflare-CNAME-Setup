<?php
if (!isset($_GET['page'])) {
	$_GET['page'] = 1;
}
?>
<a href="?action=add" class="btn btn-primary float-right mb-3"><?php echo _('Add Domain'); ?></a>
<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col"><?php echo _('Domain'); ?></th>
		<th scope="col" class="d-none d-md-table-cell"><?php echo _('Status'); ?></th>
		<th scope="col" class="d-none d-md-table-cell"><?php echo _('Mode'); ?></th>
		<th scope="col" class="d-none d-md-table-cell"><?php echo _('Operation'); ?></th>
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
		$manage_data = '<a href="?action=zone&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">';
		$manage_ssl = ' | <a href="?action=security&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">' . _('Security') . '</a>';
		$cname_method = '<span style="color:green;">' . _('Universal Setup') . '</span>';
	}
	echo '<tr>
		<td scope="col">' . $manage_data . $zone->name . '</a>
			<div class="d-block d-md-none">' . $status_translate[$zone->status] . ' | ' . $cname_method . '</div>
			<div class="d-block d-md-none">' . $manage_data . _('Manage') . '</a>' . ' |
			<a href="?action=analytics&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">' . _('Advanced Analytics') . '</a>' . $manage_ssl . '</div>
		</td>
		<td class="d-none d-md-table-cell">' . $status_translate[$zone->status] . '</td>
		<td class="d-none d-md-table-cell">' . $cname_method . '</td>
		<td class="d-none d-md-table-cell">' . $manage_data . _('Manage') . '</a>' . ' |
			<a href="?action=analytics&amp;domain=' . $zone->name . '&amp;zoneid=' . $zone->id . '">' . _('Advanced Analytics') . '</a>' . $manage_ssl . '
		</td>';
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
