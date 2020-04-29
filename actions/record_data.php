<?php
if(!isset($options)) {
	exit();
}

if ($_POST['type'] == 'CAA') {
	$options['data'] = [
		'tag' => $_POST['data_tag'],
		'value' => $_POST['data_value'],
		'flags' => intval($_POST['data_flags']),
	];
}

if ($_POST['type'] == 'SRV') {
	$options['data'] = [
		'name' => isset($_POST['srv_name'])? $_POST['srv_name'] :$_POST['name'],
		'port' => intval($_POST['srv_port']),
		'priority' => intval($_POST['srv_priority']),
		'proto' => $_POST['srv_proto'],
		'service' => $_POST['srv_service'],
		'target' => $_POST['srv_target'],
		'weight' => intval($_POST['srv_weight']),
	];
}

