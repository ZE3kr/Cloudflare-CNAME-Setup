<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 04/09/2017
 * Time: 20:16
 */

class IPsTest extends TestCase
{
    public function testListIPs()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/listIPs.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('ips')
            );

        $ips = new \Cloudflare\API\Endpoints\IPs($mock);
        $ips = $ips->listIPs();
        $this->assertObjectHasAttribute('ipv4_cidrs', $ips);
        $this->assertObjectHasAttribute('ipv6_cidrs', $ips);
    }
}
