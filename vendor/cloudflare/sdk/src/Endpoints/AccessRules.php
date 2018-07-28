<?php

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Configurations\Configurations;

class AccessRules implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     *
     * @param string $zoneID
     * @param string $scopeType
     * @param string $mode
     * @param string $configurationTarget
     * @param string $configurationValue
     * @param int $page
     * @param int $perPage
     * @param string $order
     * @param string $direction
     * @param string $match
     * @param string $notes
     * @return \stdClass
     */
    public function listRules(
        string $zoneID,
        string $scopeType = '',
        string $mode = '',
        string $configurationTarget = '',
        string $configurationValue = '',
        int $page = 1,
        int $perPage = 50,
        string $order = '',
        string $direction = '',
        string $match = 'all',
        string $notes = ''
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage,
            'match' => $match
        ];

        if (!empty($scopeType)) {
            $query['scope_type'] = $scopeType;
        }

        if (!empty($mode)) {
            $query['mode'] = $mode;
        }

        if (!empty($configurationTarget)) {
            $query['configuration_target'] = $configurationTarget;
        }

        if (!empty($configurationValue)) {
            $query['configuration_value'] = $configurationValue;
        }

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        if (!empty($notes)) {
            $query['notes'] = $notes;
        }

        $data = $this->adapter->get('zones/' . $zoneID . '/firewall/access_rules/rules', $query, []);
        $body = json_decode($data->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function createRule(
        string $zoneID,
        string $mode,
        Configurations $configuration,
        string $notes = null
    ): bool {
        $options = [
            'mode' => $mode,
            'configuration' => (object) $configuration->getArray()
        ];

        if ($notes !== null) {
            $options['notes'] = $notes;
        }

        $query = $this->adapter->post('zones/' . $zoneID . '/firewall/access_rules/rules', [], $options);

        $body = json_decode($query->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function updateRule(
        string $zoneID,
        string $ruleID,
        string $mode,
        string $notes = null
    ): bool {
        $options = [
            'mode' => $mode
        ];

        if ($notes !== null) {
            $options['notes'] = $notes;
        }

        $query = $this->adapter->patch('zones/' . $zoneID . '/firewall/access_rules/rules/' . $ruleID, [], $options);

        $body = json_decode($query->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function deleteRule(string $zoneID, string $ruleID, string $cascade = 'none'): bool
    {
        $options = [
            'cascade' => $cascade
        ];

        $data = $this->adapter->delete('zones/' . $zoneID . '/firewall/access_rules/rules/' . $ruleID, [], $options);

        $body = json_decode($data->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
