<?php
/*
 * Advanced analytics. Show the analytics data for the previous year.
 */
if (!isset($adapter)) {exit;}

$zone_name = $_GET['domain'];
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);

$zoneID = $_GET['zoneid'];
$date_now = new DateTime();

?>
<strong><?php echo strtoupper($zone_name); ?></strong> / <small><?php echo _('Advanced Analytics'); ?></small><hr>
<p id="tlorefresh" class="alert alert-primary" role="alert"><?php echo _('Loading failed. Please refresh the page to retry.'); ?></p>
<script src="js/Chart.bundle.js"></script>
<div style="height:325px"><canvas id="requests"></canvas></div>
<div style="height:325px"><canvas id="pageview"></canvas></div>
<div style="height:325px"><canvas id="bandwidth"></canvas></div>
<?php
$analytics_time = -525600;
@$analytics = $adapter->get('zones/' . $zoneID . '/analytics/dashboard', ['since' => $analytics_time]);
$analytics = json_decode($analytics->getBody());

$max_bandwidth = 0;
foreach ($analytics->result->timeseries as $key) {
	if ($key->bandwidth->all > $max_bandwidth) {
		$max_bandwidth = $key->bandwidth->all;
	}
}
$formatBytes_array = formatBytes_array($max_bandwidth);
?>
<script>
	document.getElementById('tlorefresh').style.display = 'none';
	'use strict';

	window.chartColors = {
		red: 'rgb(255, 99, 132)',
		orange: 'rgb(255, 159, 64)',
		yellow: 'rgb(255, 205, 86)',
		green: 'rgb(75, 192, 192)',
		blue: 'rgb(54, 162, 235)',
		purple: 'rgb(153, 102, 255)',
		grey: 'rgb(201, 203, 207)'
	};

	var config1 = {
		type: 'line',
		data: {
			labels: [
				<?php
foreach ($analytics->result->timeseries as $key) {
	echo '"' . explode('T', $key->since)[0] . '", ';
}
?>
			],
			datasets: [{
				label: "<?php echo _('Cached'); ?>",
				fill: true,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.red,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo $key->requests->cached . ', ';
}
?>
				],
			}, {
				label: "<?php echo _('All'); ?>",
				fill: true,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.purple,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo $key->requests->all . ', ';
}
?>
				],
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			title:{
				display:true,
				text:'<?php echo _('Requests'); ?>'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Date'); ?>'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Requests'); ?>'
					},
					ticks: {
						suggestedMin: 0
					}
				}]
			}
		}
	};

	var pageview = {
		type: 'line',
		data: {
			labels: [
				<?php
foreach ($analytics->result->timeseries as $key) {
	echo '"' . explode('T', $key->since)[0] . '", ';
}
?>
			],
			datasets: [{
				label: "<?php echo _('Unique Visitors'); ?>",
				fill: true,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.red,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo $key->uniques->all . ', ';
}
?>
				],
			}, {
				label: "<?php echo _('Page Views'); ?>",
				fill: true,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.purple,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo $key->pageviews->all . ', ';
}
?>
				],
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			title:{
				display:true,
				text:'<?php echo _('Requests'); ?>'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Date'); ?>'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Requests'); ?>'
					},
					ticks: {
						suggestedMin: 0
					}
				}]
			}
		}
	};

	var config2 = {
		type: 'line',
		data: {
			labels: [
				<?php
foreach ($analytics->result->timeseries as $key) {
	echo '"' . explode('T', $key->since)[0] . '", ';
}
?>
			],
			datasets: [{
				label: "<?php echo _('Cached'); ?>",
				fill: true,
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.red,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo round($key->bandwidth->cached / pow(1024, $formatBytes_array[2]), 2) . ', ';
}
?>
				],
			}, {
				label: "<?php echo _('All'); ?>",
				fill: true,
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.purple,
				data: [
					<?php
foreach ($analytics->result->timeseries as $key) {
	echo round($key->bandwidth->all / pow(1024, $formatBytes_array[2]), 2) . ', ';
}
?>
				],
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			title:{
				display:true,
				text:'<?php echo _('Bandwidth (Unit: '); ?><?php echo $formatBytes_array[1]; ?>)'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Date'); ?>'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: '<?php echo _('Bandwidth (Unit: '); ?><?php echo $formatBytes_array[1]; ?>)'
					},
					ticks: {
						suggestedMin: 0
					}
				}]
			}
		}
	};

	window.onload = function() {
		var ctx = document.getElementById("requests").getContext("2d");
		window.myLine = new Chart(ctx, config1);
		var ctx = document.getElementById("pageview").getContext("2d");
		window.myLine = new Chart(ctx, pageview);
		var ctx = document.getElementById("bandwidth").getContext("2d");
		window.myLine = new Chart(ctx, config2);
	};
</script>
<table class="table table-striped">
	<thead>
		<tr>
			<th scope="col"><?php echo _('Date'); ?></th>
			<th scope="col"><?php echo _('Unique Visitors'); ?></th>
			<th scope="col"><?php echo _('Page Views'); ?></th>
			<th scope="col"><?php echo _('Requests'); ?></th>
			<th scope="col"><?php echo _('Requests Hit Ratio'); ?></th>
			<th scope="col"><?php echo _('Bandwidth'); ?></th>
			<th scope="col"><?php echo _('Saved Bandwidth'); ?></th>
			<th scope="col"><?php echo _('Threats'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
echo '<tr>
		<th scope="col">' . _('Total (Last year)') . '</th>
		<th>' . number_format($analytics->result->totals->uniques->all) . '</th>
		<th>' . number_format($analytics->result->totals->pageviews->all) . '</th>
		<th>' . number_format($analytics->result->totals->requests->all) . '</th>
		<th>' . round($analytics->result->totals->requests->cached * 100 / $analytics->result->totals->requests->all, 1) . '%</th>
		<th>' . formatBytes($analytics->result->totals->bandwidth->all) . '</th>
		<th>' . round($analytics->result->totals->bandwidth->cached * 100 / $analytics->result->totals->bandwidth->all, 1) . '%</th>
		<th>' . number_format($analytics->result->totals->threats->all) . '</th>
	</tr>';
foreach ($analytics->result->timeseries as $key) {
	if ($key->requests->all != 0 && $key->bandwidth->all != 0) {
		echo '<tr>
			<th scope="col">' . explode('T', $key->since)[0] . '</th>
			<th>' . number_format($key->uniques->all) . '</th>
			<th>' . number_format($key->pageviews->all) . '</th>
			<th>' . number_format($key->requests->all) . '</th>
			<th>' . round($key->requests->cached * 100 / $key->requests->all, 1) . '%</th>
			<th>' . formatBytes($key->bandwidth->all) . '</th>
			<th>' . round($key->bandwidth->cached * 100 / $key->bandwidth->all, 1) . '%</th>
			<th>' . number_format($key->threats->all) . '</th>
		</tr>';
	}
}
?>
	</tbody>
</table>
