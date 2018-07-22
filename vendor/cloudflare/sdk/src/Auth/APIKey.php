<?php
/**
 * User: junade
 * Date: 13/01/2017
 * Time: 16:55
 */

namespace Cloudflare\API\Auth;

class APIKey implements Auth
{
    private $email;
    private $apiKey;

    public function __construct(string $email, string $apiKey)
    {
        $this->email  = $email;
        $this->apiKey = $apiKey;
    }

    public function getHeaders(): array
    {
        return [
            'X-Auth-Email'   => $this->email,
            'X-Auth-Key' => $this->apiKey
        ];
    }
}
