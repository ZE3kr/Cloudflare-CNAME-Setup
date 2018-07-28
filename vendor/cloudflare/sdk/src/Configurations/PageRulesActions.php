<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 16:50
 */

namespace Cloudflare\API\Configurations;

class PageRulesActions implements Configurations
{
    private $configs = [];

    public function setAlwaysOnline(bool $active)
    {
        $this->addConfigurationOption('always_online', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setAlwaysUseHTTPS(bool $active)
    {
        $this->addConfigurationOption('always_use_https', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setBrowserCacheTTL(int $ttl)
    {
        $this->addConfigurationOption('browser_cache_ttl', [
            'value' => $ttl
        ]);
    }

    public function setBrowserIntegrityCheck(bool $active)
    {
        $this->addConfigurationOption('browser_check', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setBypassCacheOnCookie(string $value)
    {
        if (preg_match('/^([a-zA-Z0-9\.=|_*-]+)$/i', $value) < 1) {
            throw new ConfigurationsException('Invalid cookie string.');
        }

        $this->addConfigurationOption('bypass_cache_on_cookie', [
            'value' => $value
        ]);
    }

    public function setCacheByDeviceType(bool $active)
    {
        $this->addConfigurationOption('cache_by_device_type', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setCacheKey(string $value)
    {
        $this->addConfigurationOption('cache_key', [
            'value' => $value
        ]);
    }

    public function setCacheLevel(string $value)
    {
        if (!in_array($value, ['bypass', 'basic', 'simplified', 'aggressive', 'cache_everything'])) {
            throw new ConfigurationsException('Invalid cache level');
        }

        $this->addConfigurationOption('cache_level', [
            'value' => $value
        ]);
    }

    public function setCacheOnCookie(string $value)
    {
        if (preg_match('/^([a-zA-Z0-9\.=|_*-]+)$/i', $value) < 1) {
            throw new ConfigurationsException('Invalid cookie string.');
        }

        $this->addConfigurationOption('cache_on_cookie', [
            'value' => $value
        ]);
    }

    public function setDisableApps(bool $active)
    {
        $this->addConfigurationOption('disable_apps', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setDisablePerformance(bool $active)
    {
        $this->addConfigurationOption('disable_performance', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setDisableSecurity(bool $active)
    {
        $this->addConfigurationOption('disable_security', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setEdgeCacheTTL(int $value)
    {
        if ($value > 2419200) {
            throw new ConfigurationsException('Edge Cache TTL too high.');
        }

        $this->addConfigurationOption('edge_cache_ttl', [
            'value' => $value
        ]);
    }

    public function setEmailObfuscation(bool $active)
    {
        $this->addConfigurationOption('disable_security', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setForwardingURL(int $statusCode, string $forwardingUrl)
    {
        if (!in_array($statusCode, ['301', '302'])) {
            throw new ConfigurationsException('Status Codes can only be 301 or 302.');
        }

        $this->addConfigurationOption("forwarding_url", [
            'value' => [
                'status_code' => $statusCode,
                'url' => $forwardingUrl,
            ],
        ]);
    }

    public function setHostHeaderOverride(bool $active)
    {
        $this->addConfigurationOption('host_header_override', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setHotlinkProtection(bool $active)
    {
        $this->addConfigurationOption('hotlink_protection', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setIPGeoLocationHeader(bool $active)
    {
        $this->addConfigurationOption('ip_geolocation', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setMinification(bool $html, bool $css, bool $javascript)
    {
        $this->addConfigurationOption('minification', [
            'html' => $this->getBoolAsOnOrOff($html),
            'css' => $this->getBoolAsOnOrOff($css),
            'js' => $this->getBoolAsOnOrOff($javascript),
        ]);
    }

    public function setMirage(bool $active)
    {
        $this->addConfigurationOption('mirage', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setOriginErrorPagePassthru(bool $active)
    {
        $this->addConfigurationOption('origin_error_page_pass_thru', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setQueryStringSort(bool $active)
    {
        $this->addConfigurationOption('sort_query_string_for_cache', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setDisableRailgun(bool $active)
    {
        $this->addConfigurationOption('disable_railgun', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setResolveOverride(bool $value)
    {
        $this->addConfigurationOption('resolve_override', [
            'value' => $value
        ]);
    }

    public function setRespectStrongEtag(bool $active)
    {
        $this->addConfigurationOption('respect_strong_etag', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setResponseBuffering(bool $active)
    {
        $this->addConfigurationOption('response_buffering', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setRocketLoader(string $value)
    {
        if (!in_array($value, ['off', 'manual', 'automatic'])) {
            throw new ConfigurationsException('Rocket Loader can only be off, automatic, or manual.');
        }

        $this->addConfigurationOption('rocket_loader', [
            'value' => $value
        ]);
    }

    public function setSecurityLevel(string $value)
    {
        if (!in_array($value, ['off', 'essentially_off', 'low', 'medium', 'high', 'under_attack'])) {
            throw new ConfigurationsException('Can only be set to off, essentially_off, low, medium, high or under_attack.');
        }

        $this->addConfigurationOption('security_level', [
            'value' => $value
        ]);
    }

    public function setServerSideExcludes(bool $active)
    {
        $this->addConfigurationOption('server_side_exclude', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setSmartErrors(bool $active)
    {
        $this->addConfigurationOption('smart_errors', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setSSL(string $value)
    {
        if (!in_array($value, ['off', 'flexible', 'full', 'strict', 'origin_pull'])) {
            throw new ConfigurationsException('Can only be set to off, flexible, full, strict, origin_pull.');
        }

        $this->addConfigurationOption('smart_errors', [
            'value' => $value
        ]);
    }

    public function setTrueClientIpHeader(bool $active)
    {
        $this->addConfigurationOption('true_client_ip_header', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setWAF(bool $active)
    {
        $this->addConfigurationOption('waf', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setAutomatedHTTPSRewrites(bool $active)
    {
        $this->addConfigurationOption('automatic_https_rewrites', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function setOpportunisticEncryption(bool $active)
    {
        $this->addConfigurationOption('opportunistic_encryption', [
            'value' => $this->getBoolAsOnOrOff($active)
        ]);
    }

    public function getArray(): array
    {
        return $this->configs;
    }

    private function addConfigurationOption(string $setting, array $configuration)
    {
        /**
         * Transforms an, optionally nested, array in to a collection of
         * stdClass objects.
         *
         * @var array $array
         */
        $getArrayAsObject = function (array $array) {
            return json_decode(json_encode($array));
        };

        $configuration['id'] = $setting;

        array_push($this->configs, $getArrayAsObject($configuration));
    }

    private function getBoolAsOnOrOff(bool $value): string
    {
        return true === $value ? 'on' : 'off';
    }
}
