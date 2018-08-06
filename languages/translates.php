<?php
$ttl_translate = [
	1 => _('Automatic'),
	120 => _('2 mins'),
	300 => _('5 mins'),
	600 => _('10 mins'),
	900 => _('15 mins'),
	1800 => _('30 mins'),
	3600 => _('1 hour'),
	7200 => _('2 hours'),
	18000 => _('5 hours'),
	43200 => _('12 hours'),
	86400 => _('1 day'),
];
$status_translate = [
	'active' => '<span class="badge badge-success">' . _('Active') . '</span>',
	'pending' => '<span class="badge badge-warning">' . _('Pending') . '</span>',
	'initializing' => '<span class="badge badge-light">' . _('Initializing') . '</span>',
	'moved' => '<span class="badge badge-dark">' . _('Moved') . '</span>',
	'deleted' => '<span class="badge badge-danger">' . ('Deleted') . '</span>',
	'deactivated' => '<span class="badge badge-light">' . _('Deactivated') . '</span>',
];
$action_name = [
	'logout' => _('Logout'),
	'security' => _('Security'),
	'add_record' => _('Add Record'),
	'edit_record' => _('Edit Record'),
	'delete_record' => _('Delete Record'),
	'analytics' => _('Analytics'),
	'add' => _('Add Domain'),
	'zone' => _('Manage Zone'),
	'dnssec' => _('DNSSEC'),
	'login' => _('Login'),
];
