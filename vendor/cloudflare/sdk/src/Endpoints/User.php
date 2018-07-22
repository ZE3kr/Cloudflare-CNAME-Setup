<?php
/**
 * User: junade
 * Date: 01/02/2017
 * Time: 12:30
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class User implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getUserDetails(): \stdClass
    {
        $user = $this->adapter->get('user', [], []);
        $body = json_decode($user->getBody());
        return $body->result;
    }

    public function getUserID(): string
    {
        return $this->getUserDetails()->id;
    }

    public function getUserEmail(): string
    {
        return $this->getUserDetails()->email;
    }

    public function updateUserDetails(array $details): \stdClass
    {
        $response = $this->adapter->patch('user', [], $details);
        return json_decode($response->getBody());
    }
}
