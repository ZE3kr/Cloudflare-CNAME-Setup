<?php
class CloudFlare {
	/**
	 * Sent a post to Cloudflare Partner API
	 * @param $data
	 * @return mixed
	 */
	public function postData(array $data) {
		$data['host_key'] = HOST_KEY;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/host-gw.html');
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		$res = curl_exec($ch);
		curl_close($ch);
		return json_decode($res, true);
	}

	/**
	 * "user_create" - Create a Cloudflare account mapped to your user
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.1
	 *
	 * @param string $cloudflare_email
	 * @param string $cloudflare_pass
	 * @return mixed
	 */
	public function userCreate(string $cloudflare_email, string $cloudflare_pass) {
		$data['act'] = 'user_create';
		$data['cloudflare_email'] = $cloudflare_email;
		$data['cloudflare_pass'] = $cloudflare_pass;
		$data['unique_id'] = NULL;
		$res = $this->postData($data);
		return $res;
	}

	/**
	 * "user_lookup" - Lookup a user's Cloudflare account information
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.4
	 *
	 * @return mixed
	 */
	public function userLookup() {
		$data['act'] = 'user_lookup';
		$data['cloudflare_email'] = $_COOKIE['cloudflare_email'];
		$res = $this->postData($data);
		return $res;
	}

	/**
	 * "zone_lookup" - lookup a specific user's zone
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.6
	 *
	 * @param string $zone_name
	 * @return mixed
	 */
	public function zoneLookup(string $zone_name) {
		$data['act'] = 'zone_lookup';
		$data['user_key'] = $_COOKIE['user_key'];
		$data['zone_name'] = $zone_name;
		$res = $this->postData($data);
		if ($res['response']['zone_exists'] == true) {
			return $res;
		} else {
			die(_('Error, please confirm your domain.'));
		}
	}

	/**
	 * "zone_set" - Setup a User's zone for CNAME hosting
	 * Use this to add a domain for CNAME setup or modify the existing CNAME domain's records.
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.2
	 *
	 * @param string $zone_name The zone you'd like to run CNAMES through Cloudflare for, e.g. "example.com".
	 * @param string $resolve_to The CNAME to origin server
	 * @param string $subdomains
	 * @return mixed
	 */
	public function zoneSet(string $zone_name, string $resolve_to, string $subdomains) {
		$data['act'] = 'zone_set';
		$data['user_key'] = $_COOKIE['user_key'];
		$data['zone_name'] = $zone_name;
		$data['resolve_to'] = $resolve_to;
		$data['subdomains'] = $subdomains;
		$res = $this->postData($data);
		return $res;
	}

	/**
	 * "full_zone_set" - Add a zone using the full setup method.
	 * Full setup is just like the domain added on the cloudflare.com. But it has ability to enable partner's Railgun.
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.3
	 *
	 * @param string $zone_name
	 * @return mixed
	 */
	public function zoneSet_full(string $zone_name) {
		$data['act'] = 'full_zone_set';
		$data['user_key'] = $_COOKIE['user_key'];
		$data['zone_name'] = $zone_name;
		$res = $this->postData($data);
		return $res;
	}

	/**
	 * "zone_delete" - delete a specific zone on behalf of a user
	 * @see https://www.cloudflare.com/docs/host-api/#s3.2.2
	 *
	 * @param $zone_name
	 * @return mixed
	 */
	public function zoneDelete($zone_name) {
		$data['act'] = 'zone_delete';
		$data['user_key'] = $_COOKIE['user_key'];
		$data['zone_name'] = $zone_name;
		$res = $this->postData($data);
		return $res;

	}
}

/**
 * Bulk edit a domain's DNS record.
 *
 * @param $zoneID
 * @param $subdomains
 * @param $zone_name
 * @return string
 */
function update_bind($zoneID, $subdomains, $zone_name) {
	file_put_contents('/var/www/tmp/' . $zoneID . 'txt', $subdomains);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/' . $zoneID . '/dns_records/import');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: multipart/form-data',
		'X-Auth-Email: ' . $_COOKIE['cloudflare_email'],
		'X-Auth-Key: ' . $_COOKIE['user_api_key'],
	));
	$postdata = [
		'file' => new \CurlFile('/var/www/tmp/' . $zoneID . 'txt', 'text/plain', 'bind_config.txt'),
	];
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	$data = curl_exec($ch);
	$dataarray = json_decode($data);
	if (curl_error($ch)) {
		echo 'error:' . curl_error($ch);
	}
	curl_close($ch);
	unlink('/var/www/tmp/' . $zoneID . 'txt');
	if ($dataarray->success == true) {
		return _('Updated successful.') . '<a href="/?action=zones&domain=' . $zone_name . '">' . _('Back to domain') . '</a>';
	} else {
		return $data . '<br><a href="/?action=zones&domain=' . $zone_name . '">' . _('Back to domain') . '</a>';
	}
}

/**
 * Convert bytes to a human readable text (B, KB, MB, GB, etc.).
 *
 * @param $bytes
 * @param int $precision
 * @return string
 */
function formatBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	// Uncomment one of the following alternatives
	$bytes /= pow(1024, $pow);
	// $bytes /= (1 << (10 * $pow));

	return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Convert bytes to a human readable array.
 * [0] is an integer.
 * [1] is the unit (B, KB, MB, etc.).
 * [2] is the unit number (0, 1, 2, etc.).
 *
 * @param $bytes
 * @param int $precision
 * @return array
 */
function formatBytes_array($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	// Uncomment one of the following alternatives
	$bytes /= pow(1024, $pow);
	// $bytes /= (1 << (10 * $pow));

	return [round($bytes, $precision), $units[$pow], $pow];
}

/**
 * Add resources hint header for HTTP/2 Push.
 *
 * @param string $uri the relative URI for the file to push
 * @param string $as the file type (script, style, image, etc)
 */
function h2push(string $uri, string $as) {
	global $tlo_path, $is_debug;
	if (isset($tlo_path) && !$is_debug) {
		header("Link: <{$tlo_path}{$uri}>; rel=preload; as={$as}", false);
	}
}
