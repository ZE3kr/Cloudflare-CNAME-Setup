<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 18/03/2018
 * Time: 22:23
 */

use Cloudflare\API\Endpoints\CustomHostnames;

class CustomHostnamesTest extends TestCase
{
    public function testAddHostname()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/createCustomHostname.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('post')->willReturn($response);

        $mock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/custom_hostnames'),
                $this->equalTo([
                    'hostname' => 'app.example.com',
                    'ssl' => [
                        'method' => 'http',
                        'type' => 'dv'
                    ]
                ])
            );

        $hostname = new CustomHostnames($mock);
        $hostname->addHostname('023e105f4ecef8ad9ca31a8372d0c353', 'app.example.com', 'http', 'dv');
    }

    public function testListHostnames()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/listHostnames.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/custom_hostnames'),
                $this->equalTo([
                    'hostname' => 'app.example.com',
                    'id' => '0d89c70d-ad9f-4843-b99f-6cc0252067e9',
                    'page' => 1,
                    'per_page' => 20,
                    'order' => 'ssl',
                    'direction' => 'desc',
                    'ssl' => 0
                ])
            );

        $zones = new \Cloudflare\API\Endpoints\CustomHostnames($mock);
        $result = $zones->listHostnames('023e105f4ecef8ad9ca31a8372d0c353', 'app.example.com', '0d89c70d-ad9f-4843-b99f-6cc0252067e9', 1, 20, 'ssl', 'desc', 0);

        $this->assertObjectHasAttribute('result', $result);
        $this->assertObjectHasAttribute('result_info', $result);

        $this->assertEquals('0d89c70d-ad9f-4843-b99f-6cc0252067e9', $result->result[0]->id);
        $this->assertEquals(1, $result->result_info->page);
    }

    public function testGetHostname()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getHostname.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/custom_hostnames/0d89c70d-ad9f-4843-b99f-6cc0252067e9')
            );

        $zones = new \Cloudflare\API\Endpoints\CustomHostnames($mock);
        $result = $zones->getHostname('023e105f4ecef8ad9ca31a8372d0c353', '0d89c70d-ad9f-4843-b99f-6cc0252067e9', '0d89c70d-ad9f-4843-b99f-6cc0252067e9');

        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('hostname', $result);
    }

    public function testUpdateHostname()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/updateHostname.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('patch')->willReturn($response);

        $mock->expects($this->once())
            ->method('patch')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/custom_hostnames/0d89c70d-ad9f-4843-b99f-6cc0252067e9'),
                $this->equalTo([
                    'ssl' => [
                        'method' => 'http',
                        'type' =>  'dv'
                    ]
                ])
            );

        $zones = new \Cloudflare\API\Endpoints\CustomHostnames($mock);
        $result = $zones->updateHostname('023e105f4ecef8ad9ca31a8372d0c353', '0d89c70d-ad9f-4843-b99f-6cc0252067e9', 'http', 'dv');

        $this->assertObjectHasAttribute('id', $result);
        $this->assertObjectHasAttribute('hostname', $result);
    }

    public function testDeleteHostname()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/deleteHostname.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('delete')->willReturn($response);

        $mock->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/custom_hostnames/0d89c70d-ad9f-4843-b99f-6cc0252067e9')
            );

        $zones = new \Cloudflare\API\Endpoints\CustomHostnames($mock);
        $result = $zones->deleteHostname('023e105f4ecef8ad9ca31a8372d0c353', '0d89c70d-ad9f-4843-b99f-6cc0252067e9');

        $this->assertEquals('0d89c70d-ad9f-4843-b99f-6cc0252067e9', $result->id);
    }
}
