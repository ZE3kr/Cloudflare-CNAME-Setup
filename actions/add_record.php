<?php
/*
 * Add record for specific domain.
 */

if (!isset($tlo_id)) {exit;}

if (isset($_POST['submit'])) {
	$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
	if ($_POST['proxied'] == 'false') {
		$_POST['proxied'] = false;
	}
	try {
		if ($dns->addRecord($_GET['zoneid'], $_POST['type'], $_POST['name'], $_POST['content'], $_POST['ttl'], $_POST['proxied'], $_POST['priority'])) {
			exit('<p>' . _('Success') . ', <a href="?action=add_record&amp;zoneid=' . $_GET['zoneid'] . '&domain=' . $_GET['domain'] . '">' . _('Add New Record') . '</a>, ' . _('Or') . '<a href="/?action=zones&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>');
		} else {
			exit('<p>' . _('Failed') . ', <a href="?action=add_record&zoneid=' . $_GET['zoneid'] . '&domain=' . $_GET['domain'] . '">' . _('Add New Record') . '</a>, ' . _('Or') . '<a href="/?action=zones&amp;domain=' . $_GET['domain'] . '&amp;zoneid=' . $_GET['zoneid'] . '">' . _('Go to console') . '</a></p>');
		}
	} catch (Exception $e) {
		echo $e;
	}
}
?>
<form method="POST" action="" class="am-form">
	<fieldset>
		<legend><?php echo _('Add DNS Record'); ?> <small> / <a href="/?action=zones&amp;domain=<?php echo $_GET['domain']; ?>&amp;zoneid=<?php echo $_GET['zoneid']; ?>"><?php echo _('Back'); ?></a></small></legend>
		<div class="am-form-group">
			<label for="name"><?php echo _('Record Name (e.g. “@”, “www”, etc.)'); ?></label>
			<input type="text" name="name" id="name">
		</div>
		<div class="am-form-group">
			<label for="type"><?php echo _('Record Type'); ?></label>
			<select name="type" id="type">
				<option value="A">A</option>
				<option value="AAAA">AAAA</option>
				<option value="CNAME">CNAME</option>
				<option value="TXT">TXT</option>
				<option value="SRV">SRV</option>
				<option value="LOC">LOC</option>
				<option value="MX">MX</option>
				<option value="NS">NS</option>
				<option value="SPF">SPF</option>
			</select>
		</div>
		<div class="am-form-group">
			<label for="doc-ta-1"><?php echo _('Record Content'); ?></label>
			<textarea name="content" rows="5" id="doc-ta-1"></textarea>
		</div>
		<div class="am-form-group">
			<label for="ttl">TTL</label>
			<select name="ttl" id="ttl">
				<?php
foreach ($ttl_translate as $_ttl => $_ttl_name) {
	echo '<option value="' . $_ttl . '">' . $_ttl_name . '</option>';
}
?>
			</select>
		</div>
		<div class="am-form-group">
			<label for="proxied">CDN</label>
			<select name="proxied" id="proxied">
				<option value="true"><?php echo _('On'); ?></option>
				<option value="false"><?php echo _('Off'); ?></option>
			</select>
		</div>
		<div class="am-form-group">
			<label for="priority"><?php echo _('Priority (Only for MX record)'); ?></label>
			<input type="number" name="priority" id="priority" step="1" min="1" value="10">
		</div>
		<p><button type="submit" name="submit" class="am-btn am-btn-default"><?php echo _('Submit'); ?></button></p>
	</fieldset>
</form>
