<?php
$dns_data = [];

if ($_POST['type'] == 'CAA') {
	$dns_data = [
		'tag' => $_POST['data_tag'],
		'value' => $_POST['data_value'],
		'flags' => intval($_POST['data_flags']),
	];
}
