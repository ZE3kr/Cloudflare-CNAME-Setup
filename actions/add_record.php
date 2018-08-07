<?php
/*
 * Add record for specific domain.
 */

if (!isset($tlo_id)) {exit;}

if (isset($_POST['submit'])) {
	if ($_POST['proxied'] == 'false') {
		$_POST['proxied'] = false;
	} else {
		$_POST['proxied'] = true;
	}
	if ($_POST['type'] != 'A' && $_POST['type'] != 'AAAA' && $_POST['type'] != 'CNAME') {
		$_POST['proxied'] = false;
	}
	try {
		$options = [
			'type' => $_POST['type'],
			'name' => $_POST['name'],
			'content' => $_POST['content'],
			'proxied' => $_POST['proxied'],
			'ttl' => $_POST['ttl'],
			'priority' => intval($_POST['priority']),

		];
		$dns = $adapter->post('zones/' . $_GET['zoneid'] . '/dns_records', [], $options);
		$dns = json_decode($dns->getBody());
		if (isset($dns->result->id)) {
			exit('<p class="alert alert-success" role="alert">' . _('Success') . ', <a href="?action=add_record&amp;zoneid=' . $_GET['zoneid'] . '&domain=' . $_GET['domain'] . '">' . _('Add New Record') . '</a>, ' . _('Or') . '<a href="?action=zone&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>');
		} else {
			exit('<p class="alert alert-danger" role="alert">' . _('Failed') . ', <a href="?action=add_record&amp;zoneid=' . $_GET['zoneid'] . '&domain=' . $_GET['domain'] . '">' . _('Add New Record') . '</a>, ' . _('Or') . '<a href="?action=zone&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>');
		}
	} catch (Exception $e) {
		echo '<p class="alert alert-danger" role="alert">' . _('Failed') . '</p>';
		echo '<div class="alert alert-warning" role="alert">' . $e->getMessage() . '</div>';
	}
}
?>
<strong><?php echo '<h1 class="h5"><a href="?action=zone&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">&lt;- ' . _('Back') . '</a></h1>'; ?></strong><hr>
<form method="POST" action="">
	<fieldset>
		<legend><?php echo _('Add DNS Record'); ?></legend>
		<div class="form-group">
			<label for="name"><?php echo _('Record Name (e.g. “@”, “www”, etc.)'); ?></label>
			<input type="text" name="name" id="name" class="form-control">
		</div>
		<div class="form-group">
			<label for="type"><?php echo _('Record Type'); ?></label>
			<select name="type" id="type" class="form-control">
				<option value="A">A</option>
				<option value="AAAA">AAAA</option>
				<option value="CNAME">CNAME</option>
				<option value="MX">MX</option>
				<option value="SPF">SPF</option>
				<option value="TXT">TXT</option>
				<option value="NS">NS</option>
				<option value="PTR">PTR</option>
			</select>
		</div>
		<div class="form-group">
			<label for="doc-ta-1"><?php echo _('Record Content'); ?></label>
			<textarea name="content" rows="5" id="doc-ta-1" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<label for="ttl">TTL</label>
			<select name="ttl" id="ttl" class="form-control">
				<?php
foreach ($ttl_translate as $_ttl => $_ttl_name) {
	echo '<option value="' . $_ttl . '">' . $_ttl_name . '</option>';
}
?>
			</select>
		</div>
		<div class="form-group">
			<label for="proxied">CDN</label>
			<select name="proxied" id="proxied" class="form-control">
				<option value="true"><?php echo _('On'); ?></option>
				<option value="false"><?php echo _('Off'); ?></option>
			</select>
		</div>
		<div class="form-group">
			<label for="priority"><?php echo _('Priority (Only for MX record)'); ?></label>
			<input type="number" name="priority" id="priority" step="1" min="1" value="1" class="form-control">
		</div>
		<p><button type="submit" name="submit" class="btn btn-primary"><?php echo _('Submit'); ?></button></p>
	</fieldset>
</form>
