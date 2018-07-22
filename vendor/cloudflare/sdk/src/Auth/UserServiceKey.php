<?php
/**
 * User: junade
 * Date: 13/01/2017
 * Time: 18:01
 */

namespace Cloudflare\API\Auth;

class UserServiceKey implements Auth
{
    private $userServiceKey;

    public function __construct(string $userServiceKey)
    {
        $this->userServiceKey = $userServiceKey;
    }

    public function getHeaders(): array
    {
        return [
            'X-Auth-User-Service-Key' => $this->userServiceKey,
        ];
    }
}
