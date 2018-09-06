<?php

/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 15:24
 */
class ConfigurationsUARulesTest extends TestCase
{
    public function testGetArray()
    {
        $configuration = new \Cloudflare\API\Configurations\UARules();
        $configuration->addUA('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/603.2.4 (KHTML, like Gecko) Version/10.1.1 Safari/603.2.4');

        $array = $configuration->getArray();
        $this->assertCount(1, $array);

        $this->assertArrayHasKey('target', $array[0]);
        $this->assertEquals('ua', $array[0]['target']);
        $this->assertArrayHasKey('value', $array[0]);
        $this->assertEquals(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/603.2.4 (KHTML, like Gecko) Version/10.1.1 Safari/603.2.4',
            $array[0]['value']
        );
    }
}
