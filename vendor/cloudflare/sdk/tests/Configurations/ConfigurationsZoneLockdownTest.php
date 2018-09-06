<?php

/**
 * Created by PhpStorm.
 * User: junade
 * Date: 05/09/2017
 * Time: 13:50
 */
class ConfigurationsZoneLockdownTest extends TestCase
{
    public function testGetArray()
    {
        $configuration = new \Cloudflare\API\Configurations\ZoneLockdown();
        $configuration->addIP('1.2.3.4');

        $array = $configuration->getArray();
        $this->assertCount(1, $array);

        $this->assertArrayHasKey('target', $array[0]);
        $this->assertEquals('ip', $array[0]['target']);
        $this->assertArrayHasKey('value', $array[0]);
        $this->assertEquals('1.2.3.4', $array[0]['value']);

        $configuration->addIPRange('1.2.3.4/24');

        $array = $configuration->getArray();
        $this->assertCount(2, $array);

        $this->assertArrayHasKey('target', $array[1]);
        $this->assertEquals('ip_range', $array[1]['target']);
        $this->assertArrayHasKey('value', $array[1]);
        $this->assertEquals('1.2.3.4/24', $array[1]['value']);
    }
}
