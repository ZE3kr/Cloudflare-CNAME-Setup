<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 15:17
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Configurations\Configurations;
use Cloudflare\API\Adapter\Adapter;

class UARules implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listRules(
        string $zoneID,
        int $page = 1,
        int $perPage = 20
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage
        ];

        $user = $this->adapter->get('zones/' . $zoneID . '/firewall/ua_rules', $query, []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function createRule(
        string $zoneID,
        string $mode,
        Configurations $configuration,
        string $ruleID = null,
        string $description = null
    ): bool {
        $options = [
            'mode' => $mode,
            'configurations' => $configuration->getArray()
        ];

        if ($ruleID !== null) {
            $options['id'] = $ruleID;
        }

        if ($description !== null) {
            $options['description'] = $description;
        }

        $user = $this->adapter->post('zones/' . $zoneID . '/firewall/ua_rules', [], $options);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function getRuleDetails(string $zoneID, string $blockID): \stdClass
    {
        $user = $this->adapter->get('zones/' . $zoneID . '/firewall/ua_rules/' . $blockID, [], []);
        $body = json_decode($user->getBody());
        return $body->result;
    }

    public function updateRule(
        string $zoneID,
        string $ruleID,
        string $mode,
        \Cloudflare\API\Configurations\UARules $configuration,
        string $description = null
    ): bool {
        $options = [
            'mode' => $mode,
            'id' => $ruleID,
            'configurations' => $configuration->getArray()
        ];

        if ($description !== null) {
            $options['description'] = $description;
        }

        $user = $this->adapter->put('zones/' . $zoneID . '/firewall/ua_rules/' . $ruleID, [], $options);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function deleteRule(string $zoneID, string $ruleID): bool
    {
        $user = $this->adapter->delete('zones/' . $zoneID . '/firewall/ua_rules/' . $ruleID, [], []);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
