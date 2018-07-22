<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 05/09/2017
 * Time: 13:43
 */

namespace Cloudflare\API\Configurations;

class ZoneLockdown implements Configurations
{
    private $configs = [];

    public function addIP(string $value)
    {
        $this->configs[] = (object)['target' => 'ip', 'value' => $value];
    }

    public function addIPRange(string $value)
    {
        $this->configs[] = (object)['target' => 'ip_range', 'value' => $value];
    }

    public function getArray(): array
    {
        return $this->configs;
    }
}
