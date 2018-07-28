<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 23/10/2017
 * Time: 11:17
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class WAF implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getPackages(
        string $zoneID,
        int $page = 1,
        int $perPage = 20,
        string $order = '',
        string $direction = '',
        string $match = 'all'
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage,
            'match' => $match
        ];

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $user = $this->adapter->get('zones/' . $zoneID . '/firewall/waf/packages', $query, []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }


    public function getPackageInfo(
        string $zoneID,
        string $packageID
    ): \stdClass {
        $user = $this->adapter->get('zones/' . $zoneID . '/firewall/waf/packages/' . $packageID, [], []);
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function getRules(
        string $zoneID,
        string $packageID,
        int $page = 1,
        int $perPage = 20,
        string $order = '',
        string $direction = '',
        string $match = 'all'
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage,
            'match' => $match
        ];

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }
        $user = $this->adapter->get('zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/rules', $query, []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function getRuleInfo(
        string $zoneID,
        string $packageID,
        string $ruleID
    ): \stdClass {
        $user = $this->adapter->get(
            'zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/rules/' . $ruleID,
            [],
            []
        );
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function updateRule(
        string $zoneID,
        string $packageID,
        string $ruleID,
        string $status
    ): \stdClass {
        $query = [
            'mode' => $status,
        ];

        $user = $this->adapter->patch(
            'zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/rules/' . $ruleID,
            [],
            $query
        );
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function getGroups(
        string $zoneID,
        string $packageID,
        int $page = 1,
        int $perPage = 20,
        string $order = '',
        string $direction = '',
        string $match = 'all'
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage,
            'match' => $match
        ];

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $user = $this->adapter->get(
            'zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/groups',
            $query,
            []
        );
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function getGroupInfo(
        string $zoneID,
        string $packageID,
        string $groupID
    ): \stdClass {
        $user = $this->adapter->get(
            'zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/groups/' . $groupID,
            [],
            []
        );
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function updateGroup(
        string $zoneID,
        string $packageID,
        string $groupID,
        string $status
    ): \stdClass {
        $query = [
            'mode' => $status
        ];

        $user = $this->adapter->patch(
            'zones/' . $zoneID . '/firewall/waf/packages/' . $packageID . '/groups/' . $groupID,
            [],
            $query
        );
        $body = json_decode($user->getBody());

        return $body->result;
    }
}
