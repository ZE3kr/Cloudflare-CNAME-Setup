<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 06/06/2017
 * Time: 15:45
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class Zones implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $name
     * @param bool $jumpStart
     * @param string $organizationID
     * @return \stdClass
     */
    public function addZone(string $name, bool $jumpStart = false, string $organizationID = ''): \stdClass
    {
        $options = [
            'name' => $name,
            'jump_start' => $jumpStart
        ];

        if (!empty($organizationID)) {
            $options['organization'] = (object)['id' => $organizationID];
        }

        $user = $this->adapter->post('zones', [], $options);
        $body = json_decode($user->getBody());
        return $body->result;
    }

    public function activationCheck(string $zoneID): bool
    {
        $user = $this->adapter->put('zones/' . $zoneID . '/activation_check', [], []);
        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function listZones(
        string $name = '',
        string $status = '',
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

        if (!empty($name)) {
            $query['name'] = $name;
        }

        if (!empty($status)) {
            $query['status'] = $status;
        }

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $user = $this->adapter->get('zones', $query, []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function getZoneID(string $name = ''): string
    {
        $zones = $this->listZones($name);

        if (count($zones->result) < 1) {
            throw new EndpointException('Could not find zones with specified name.');
        }

        return $zones->result[0]->id;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $zoneID
     * @param string $since
     * @param string $until
     * @param bool $continuous
     * @return \stdClass
     */
    public function getAnalyticsDashboard(string $zoneID, string $since = '-10080', string $until = '0', bool $continuous = true): \stdClass
    {
        $response = $this->adapter->get('zones/' . $zoneID . '/analytics/dashboard', ['since' => $since, 'until' => $until, 'continuous' => var_export($continuous, true)], []);

        return json_decode($response->getBody())->result;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $zoneID
     * @param bool $enable
     * @return bool
     */
    public function changeDevelopmentMode(string $zoneID, bool $enable = false): bool
    {
        $response = $this->adapter->patch('zones/' . $zoneID . '/settings/development_mode', [], ['value' => $enable ? 'on' : 'off']);

        $body = json_decode($response->getBody());

        if ($body->success) {
            return true;
        }

        return false;
    }


    /**
     * Purge Everything
     * @param string $zoneID
     * @return bool
     */
    public function cachePurgeEverything(string $zoneID): bool
    {
        $user = $this->adapter->delete('zones/' . $zoneID . '/purge_cache', [], ['purge_everything' => true]);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function cachePurge(string $zoneID, array $files = null, array $tags = null): bool
    {
        if ($files === null && $tags === null) {
            throw new EndpointException('No files or tags to purge.');
        }

        $options = [
            'files' => $files,
            'tags' => $tags
        ];

        $user = $this->adapter->delete('zones/' . $zoneID . '/purge_cache', [], $options);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
