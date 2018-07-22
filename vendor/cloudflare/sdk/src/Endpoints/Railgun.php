<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 23/10/2017
 * Time: 11:15
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class Railgun implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function create(
        string $name
    ): \stdClass {
        $query = [
            'name' => $name,
        ];

        $user = $this->adapter->post('railguns', [], $query);
        $body = json_decode($user->getBody());

        return $body;
    }

    public function list(
        int $page = 1,
        int $perPage = 20,
        string $direction = ''
    ): \stdClass {
        $query = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if (!empty($direction)) {
            $query['direction'] = $direction;
        }

        $user = $this->adapter->get('railguns', $query, []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function get(
        string $railgunID
    ): \stdClass {
        $user = $this->adapter->get('railguns/' . $railgunID, [], []);
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function getZones(
        string $railgunID
    ): \stdClass {
        $user = $this->adapter->get('railguns/' . $railgunID . '/zones', [], []);
        $body = json_decode($user->getBody());

        return (object)['result' => $body->result, 'result_info' => $body->result_info];
    }

    public function update(
        string $railgunID,
        bool $status
    ): \stdClass {
        $query = [
            'enabled' => $status
        ];

        $user = $this->adapter->patch('railguns/' . $railgunID, [], $query);
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function delete(
        string $railgunID
    ): bool {
        $user = $this->adapter->delete('railguns/' . $railgunID, [], []);
        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
