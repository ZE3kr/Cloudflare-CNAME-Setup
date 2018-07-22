<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 15:22
 */

namespace Cloudflare\API\Configurations;

class UARules implements Configurations
{
    private $configs = [];

    public function addUA(string $value)
    {
        $this->configs[] = (object)['target' => 'ua', 'value' => $value];
    }

    public function getArray(): array
    {
        return $this->configs;
    }
}
