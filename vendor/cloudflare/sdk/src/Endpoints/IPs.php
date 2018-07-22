<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 04/09/2017
 * Time: 19:56
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

class IPs implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listIPs(): \stdClass
    {
        $ips = $this->adapter->get('ips', [], []);
        $body = json_decode($ips->getBody());

        return $body->result;
    }
}
