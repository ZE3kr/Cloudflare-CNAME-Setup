<?php
/**
 * User: junade
 * Date: 01/02/2017
 * Time: 12:31
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;

interface API
{
    public function __construct(Adapter $adapter);
}
