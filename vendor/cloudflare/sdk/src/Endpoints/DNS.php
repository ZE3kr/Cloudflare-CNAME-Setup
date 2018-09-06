<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 09/06/2017
 * Time: 15:14
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class DNS implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $zoneID
     * @param string $type
     * @param string $name
     * @param string $content
     * @param int $ttl
     * @param bool $proxied
     * @param string $priority
     * @return bool
     */
    public function addRecord(
        string $zoneID,
        string $type,
        string $name,
        string $content,
        int $ttl = 0,
        bool $proxied = true,
        string $priority = ''
    ): bool {
        $options = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied
        ];

        if ($ttl > 0) {
            $options['ttl'] = $ttl;
        }

        if (!empty($priority)) {
            $options['priority'] = (int)$priority;
        }

        $user = $this->adapter->post('zones/' . $zoneID . '/dns_records', $options);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function listRecords(
        string $zoneID,
        string $type = '',
        string $name = '',
        string $content = '',
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

        if (!empty($type)) {
            $query['type'] = $type;
        }

        if (!empty($name)) {
            $query['name'] = $name;
        }

        if (!empty($content)) {
            $query['content'] = $content;
        }

        if (!empty($order)) {
            $query['order'] = $order;
        }

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $user = $this->adapter->get('zones/' . $zoneID . '/dns_records', $query);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function getRecordDetails(string $zoneID, string $recordID): \stdClass
    {
        $user = $this->adapter->get('zones/' . $zoneID . '/dns_records/' . $recordID);
        $body = json_decode($user->getBody());
        return $body->result;
    }

    public function updateRecordDetails(string $zoneID, string $recordID, array $details): \stdClass
    {
        $response = $this->adapter->put('zones/' . $zoneID . '/dns_records/' . $recordID, $details);
        return json_decode($response->getBody());
    }

    public function deleteRecord(string $zoneID, string $recordID): bool
    {
        $user = $this->adapter->delete('zones/' . $zoneID . '/dns_records/' . $recordID);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
