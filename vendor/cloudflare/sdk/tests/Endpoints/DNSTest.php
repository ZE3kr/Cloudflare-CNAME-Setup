<?php

/**
 * Created by PhpStorm.
 * User: junade
 * Date: 09/06/2017
 * Time: 15:31
 */
class DNSTest extends TestCase
{
    public function testAddRecord()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/addRecord.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('post')->willReturn($response);

        $mock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/dns_records'),
                $this->equalTo([
                    'type' => 'A',
                    'name' => 'example.com',
                    'content' => '127.0.0.1',
                    'ttl' => 120,
                    'proxied' => false
                ])
            );

        $dns = new \Cloudflare\API\Endpoints\DNS($mock);
        $dns->addRecord('023e105f4ecef8ad9ca31a8372d0c353', 'A', 'example.com', '127.0.0.1', '120', false);
    }

    public function testListRecords()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/listRecords.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/dns_records'),
                $this->equalTo([
                    'page' => 1,
                    'per_page' => 20,
                    'match' => 'all',
                    'type' => 'A',
                    'name' => 'example.com',
                    'content' => '127.0.0.1',
                    'order' => 'type',
                    'direction' => 'desc',
                ])
            );

        $zones = new \Cloudflare\API\Endpoints\DNS($mock);
        $result = $zones->listRecords('023e105f4ecef8ad9ca31a8372d0c353', 'A', 'example.com', '127.0.0.1', 1, 20, 'type', 'desc');

        $this->assertObjectHasAttribute('result', $result);
        $this->assertObjectHasAttribute('result_info', $result);

        $this->assertEquals('372e67954025e0ba6aaa6d586b9e0b59', $result->result[0]->id);
        $this->assertEquals(1, $result->result_info->page);
    }

    public function testGetDNSRecordDetails()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getDNSRecordDetails.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/dns_records/372e67954025e0ba6aaa6d586b9e0b59')
            );

        $dns = new \Cloudflare\API\Endpoints\DNS($mock);
        $result = $dns->getRecordDetails('023e105f4ecef8ad9ca31a8372d0c353', '372e67954025e0ba6aaa6d586b9e0b59');

        $this->assertEquals('372e67954025e0ba6aaa6d586b9e0b59', $result->id);
    }

    public function testUpdateDNSRecord()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/updateDNSRecord.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('put')->willReturn($response);

        $details = [
            'type' => 'A',
            'name' => 'example.com',
            'content' => '1.2.3.4',
            'ttl' => 120,
            'proxied' => false,
        ];

        $mock->expects($this->once())
            ->method('put')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/dns_records/372e67954025e0ba6aaa6d586b9e0b59'),
                $this->equalTo($details)
            );

        $dns = new \Cloudflare\API\Endpoints\DNS($mock);
        $result = $dns->updateRecordDetails('023e105f4ecef8ad9ca31a8372d0c353', '372e67954025e0ba6aaa6d586b9e0b59', $details);

        $this->assertEquals('372e67954025e0ba6aaa6d586b9e0b59', $result->result->id);

        foreach ($details as $property => $value) {
            $this->assertEquals($result->result->{ $property }, $value);
        }
    }
}
