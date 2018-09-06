<?php

/**
 * Created by PhpStorm.
 * User: junade
 * Date: 04/09/2017
 * Time: 21:23
 */
class ZoneLockdownTest extends TestCase
{
    public function testListLockdowns()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/listLockdowns.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/firewall/lockdowns'),
                $this->equalTo([
                    'page' => 1,
                    'per_page' => 20,
                ])
            );

        $zones = new \Cloudflare\API\Endpoints\ZoneLockdown($mock);
        $result = $zones->listLockdowns('023e105f4ecef8ad9ca31a8372d0c353');

        $this->assertObjectHasAttribute('result', $result);
        $this->assertObjectHasAttribute('result_info', $result);

        $this->assertEquals('372e67954025e0ba6aaa6d586b9e0b59', $result->result[0]->id);
        $this->assertEquals(1, $result->result_info->page);
    }

    public function testAddLockdown()
    {
        $config = new \Cloudflare\API\Configurations\ZoneLockdown();
        $config->addIP('1.2.3.4');

        $response = $this->getPsr7JsonResponseForFixture('Endpoints/addLockdown.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('post')->willReturn($response);

        $mock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/firewall/lockdowns'),
                $this->equalTo([
                    'urls' => ['api.mysite.com/some/endpoint*'],
                    'id' => '372e67954025e0ba6aaa6d586b9e0b59',
                    'description' => 'Restrict access to these endpoints to requests from a known IP address',
                    'configurations' => $config->getArray(),
                ])
            );

        $zoneLockdown = new \Cloudflare\API\Endpoints\ZoneLockdown($mock);
        $zoneLockdown->createLockdown(
            '023e105f4ecef8ad9ca31a8372d0c353',
            ['api.mysite.com/some/endpoint*'],
            $config,
            '372e67954025e0ba6aaa6d586b9e0b59',
            'Restrict access to these endpoints to requests from a known IP address'
        );
    }

    public function testGetRecordDetails()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getRecordDetails.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/firewall/lockdowns/372e67954025e0ba6aaa6d586b9e0b59')
            );

        $lockdown = new \Cloudflare\API\Endpoints\ZoneLockdown($mock);
        $result = $lockdown->getLockdownDetails('023e105f4ecef8ad9ca31a8372d0c353', '372e67954025e0ba6aaa6d586b9e0b59');

        $this->assertEquals('372e67954025e0ba6aaa6d586b9e0b59', $result->id);
    }

    public function testUpdateLockdown()
    {
        $config = new \Cloudflare\API\Configurations\ZoneLockdown();
        $config->addIP('1.2.3.4');

        $response = $this->getPsr7JsonResponseForFixture('Endpoints/updateLockdown.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('put')->willReturn($response);

        $mock->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/firewall/lockdowns/372e67954025e0ba6aaa6d586b9e0b59'),
                $this->equalTo([
                    'urls' => ['api.mysite.com/some/endpoint*'],
                    'id' => '372e67954025e0ba6aaa6d586b9e0b59',
                    'description' => 'Restrict access to these endpoints to requests from a known IP address',
                    'configurations' => $config->getArray(),
                ])
            );

        $zoneLockdown = new \Cloudflare\API\Endpoints\ZoneLockdown($mock);
        $zoneLockdown->updateLockdown(
            '023e105f4ecef8ad9ca31a8372d0c353',
            '372e67954025e0ba6aaa6d586b9e0b59',
            ['api.mysite.com/some/endpoint*'],
            $config,
            'Restrict access to these endpoints to requests from a known IP address'
        );
    }

    public function testDeleteLockdown()
    {
        $config = new \Cloudflare\API\Configurations\ZoneLockdown();
        $config->addIP('1.2.3.4');

        $response = $this->getPsr7JsonResponseForFixture('Endpoints/deleteLockdown.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('delete')->willReturn($response);

        $mock->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/firewall/lockdowns/372e67954025e0ba6aaa6d586b9e0b59')
            );

        $zoneLockdown = new \Cloudflare\API\Endpoints\ZoneLockdown($mock);
        $zoneLockdown->deleteLockdown('023e105f4ecef8ad9ca31a8372d0c353', '372e67954025e0ba6aaa6d586b9e0b59');
    }
}
