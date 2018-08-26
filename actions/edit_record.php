<?php
/*
 * Edit a record.
 */

if (!isset($tlo_id)) {exit;}
?>
<strong><?php echo '<h1 class="h5"><a href="?action=zone&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">&lt;- ' . _('Back') . '</a></h1>'; ?></strong><hr>
<?php
$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
$dns_details = $dns->getRecordDetails($_GET['zoneid'], $_GET['recordid']);
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
		'ttl' => intval($_POST['ttl']),
	];
	if ($_POST['type'] == 'MX') {
		$options['priority'] = intval($_POST['priority']);
	}
	try {
		$dns = $adapter->put('zones/' . $_GET['zoneid'] . '/dns_records/' . $_GET['recordid'], [], $options);
		$dns = json_decode($dns->getBody());
		if (isset($dns->result->id)) {
			exit('<p class="alert alert-success" role="alert">' . _('Success') . '</p>');
		} else {
			echo '<p class="alert alert-danger" role="alert">' . _('Failed') . '</p>';
		}
	} catch (Exception $e) {
		echo '<p class="alert alert-danger" role="alert">' . _('Failed') . '</p>';
		echo '<div class="alert alert-warning" role="alert">' . $e->getMessage() . '</div>';
	}
}
if (isset($msg)) {echo $msg;}
?>
<form method="POST" action="">
	<fieldset>
		<legend><?php echo _('Edit DNS Record'); ?></legend>
		<div class="form-group">
			<label for="name"><?php echo _('Record Name (e.g. “@”, “www”, etc.)'); ?></label>
			<input type="text" name="name" id="name" value="<?php echo $dns_details->name; ?>" class="form-control">
		</div>
		<div class="form-group">
			<label for="type"><?php echo _('Record Type'); ?></label>
			<select name="type" id="type" disabled="disabled" class="form-control">
				<option value="<?php echo $dns_details->type; ?>"><?php echo $dns_details->type; ?></option>
			</select>
		</div>
		<div class="form-group">
			<label for="doc-ta-1"><?php echo _('Record Content'); ?></label>
			<textarea name="content" rows="5" id="doc-ta-1" class="form-control"><?php echo $dns_details->content; ?></textarea>
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
		<?php if ($dns_details->proxiable) {?>
		<div class="form-group">
			<label for="proxied">CDN</label>
			<select name="proxied" id="proxied" class="form-control">
				<option value="true" <?php if ($dns_details->proxied) {echo 'selected="selected"';}?>><?php echo _('On'); ?></option>
				<option value="false" <?php if (!$dns_details->proxied) {echo 'selected="selected"';}?>><?php echo _('Off'); ?></option>
			</select>
		</div>
		<?php }?>
		<?php if ($dns_details->type == 'MX' || $dns_details->type == 'SRV') {?>
		<div class="form-group">
			<label for="priority"><?php echo _('Priority (Only for MX record)'); ?></label>
			<input type="number" name="priority" id="priority" step="1" min="1" value="<?php echo $dns_details->priority; ?>" class="form-control">
		</div>
		<?php }?>

		<button type="submit" name="submit" class="btn btn-primary"><?php echo _('Submit'); ?></button>
	</fieldset>
</form>
