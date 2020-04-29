<?php
/*
 * Add record for specific domain.
 */

if (!isset($adapter)) {exit;}

if (isset($_POST['submit'])) {
	if ($_POST['proxied'] == 'false') {
		$_POST['proxied'] = false;
	} else {
		$_POST['proxied'] = true;
	}
	if ($_POST['type'] != 'A' && $_POST['type'] != 'AAAA' && $_POST['type'] != 'CNAME') {
		$_POST['proxied'] = false;
	}

	$options = [
		'type' => $_POST['type'],
		'name' => $_POST['name'],
		'content' => $_POST['content'],
		'proxied' => $_POST['proxied'],
		'ttl' => intval($_POST['ttl'])
	];

	include "record_data.php";

	if ($_POST['type'] == 'MX') {
		$options['priority'] = intval($_POST['priority']);
	}
	try {
		$dns = $adapter->post('zones/' . $_GET['zoneid'] . '/dns_records', $options);
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
				<option value="CAA">CAA</option>
				<option value="SRV">SRV</option>
			</select>
		</div>

		<div class="form-group" id="dns-content">
			<label for="doc-ta-1"><?php echo _('Record Content'); ?></label>
			<textarea name="content" rows="5" id="doc-ta-1" class="form-control"></textarea>
		</div>

		<div class="form-group" id="dns-mx-priority">
			<label for="priority"><?php echo _('Priority'); ?></label>
			<input type="number" name="priority" id="priority" step="1" min="1" value="1" class="form-control">
		</div>

		<div id="dns-data-caa">
			<div class="form-group">
				<label for="data_tag"><?php echo _('Tag'); ?></label>
				<select name="data_tag" id="data_tag" class="form-control">
					<option value="issue" selected="selected"><?php echo _('Only allow specific hostnames') ?></option>
					<option value="issuewild"><?php echo _('Only allow wildcards') ?></option>
					<option value="iodef"><?php echo _('Send violation reports to URL (http:, https:, or mailto:)') ?></option>
				</select>
			</div>
			<div class="form-group">
				<label for="data_value"><?php echo _('Value'); ?></label>
				<input type="text" name="data_value" id="data_value" class="form-control">
			</div>
			<input type="hidden" name="data_flags" value="0">
		</div>

		<div id="dns-data-srv">
			<div class="form-group">
				<label for="srv_service"><?php echo _('Service'); ?></label>
				<input type="text" name="srv_service" id="srv_service" value="_sip" class="form-control">
			</div>
			<div class="form-group">
				<label for="srv_proto"><?php echo _('Proto'); ?></label>
				<select name="srv_proto" id="srv_proto" class="form-control">
					<option value="_tcp" selected="selected">TCP</option>
					<option value="_udp">UDP</option>
					<option value="_tls">TLS</option>
				</select>
			</div>
			<div class="form-group">
				<label for="srv_priority"><?php echo _('Priority'); ?></label>
				<input type="text" name="srv_priority" id="srv_priority" value="1" class="form-control">
			</div>
			<div class="form-group">
				<label for="srv_weight"><?php echo _('Weight'); ?></label>
				<input type="text" name="srv_weight" id="srv_weight" value="1" class="form-control">
			</div>
			<div class="form-group">
				<label for="srv_port"><?php echo _('Port'); ?></label>
				<input type="text" name="srv_port" id="srv_port" value="1" class="form-control">
			</div>
			<div class="form-group">
				<label for="srv_target"><?php echo _('Target'); ?></label>
				<input type="text" name="srv_target" id="srv_target" class="form-control">
			</div>
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
		<p><button type="submit" name="submit" class="btn btn-primary"><?php echo _('Submit'); ?></button></p>
	</fieldset>
	<script>

	</script>
</form>
