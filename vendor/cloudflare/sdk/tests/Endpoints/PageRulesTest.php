<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 19:25
 */

class PageRulesTest extends TestCase
{
    public function testCreatePageRule()
    {
        $target = new \Cloudflare\API\Configurations\PageRulesTargets('*example.com/images/*');
        $action = new \Cloudflare\API\Configurations\PageRulesActions();
        $action->setAlwaysOnline(true);

        $response = $this->getPsr7JsonResponseForFixture('Endpoints/createPageRule.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('post')->willReturn($response);

        $mock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/pagerules'),
                $this->equalTo([
                    'targets' => $target->getArray(),
                    'actions' => $action->getArray(),
                    'status' => 'active',
                    'priority' => 1
                ])
            );

        $pageRules = new \Cloudflare\API\Endpoints\PageRules($mock);
        $result = $pageRules->createPageRule('023e105f4ecef8ad9ca31a8372d0c353', $target, $action, true, 1);

        $this->assertTrue($result);
    }

    public function testListPageRules()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/listPageRules.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/pagerules'),
              $this->equalTo([
                'status' => 'active',
                'order' => 'status',
                'direction' => 'desc',
                'match' => 'all'
              ])
            );

        $pageRules = new \Cloudflare\API\Endpoints\PageRules($mock);
        $pageRules->listPageRules('023e105f4ecef8ad9ca31a8372d0c353', 'active', 'status', 'desc', 'all');
    }

    public function testGetPageRuleDetails()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getPageRuleDetails.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/pagerules/9a7806061c88ada191ed06f989cc3dac')
            );

        $pageRules = new \Cloudflare\API\Endpoints\PageRules($mock);
        $pageRules->getPageRuleDetails('023e105f4ecef8ad9ca31a8372d0c353', '9a7806061c88ada191ed06f989cc3dac');
    }

    public function testUpdatePageRule()
    {
        $target = new \Cloudflare\API\Configurations\PageRulesTargets('*example.com/images/*');
        $action = new \Cloudflare\API\Configurations\PageRulesActions();
        $action->setAlwaysOnline(true);

        $response = $this->getPsr7JsonResponseForFixture('Endpoints/updatePageRule.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('patch')->willReturn($response);

        $mock->expects($this->once())
            ->method('patch')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/pagerules'),
                $this->equalTo([
                    'targets' => $target->getArray(),
                    'actions' => $action->getArray(),
                    'status' => 'active',
                    'priority' => 1
                ])
            );

        $pageRules = new \Cloudflare\API\Endpoints\PageRules($mock);
        $result = $pageRules->updatePageRule('023e105f4ecef8ad9ca31a8372d0c353', $target, $action, true, 1);

        $this->assertTrue($result);
    }

    public function testDeletePageRule()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/deletePageRule.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('delete')->willReturn($response);

        $mock->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('zones/023e105f4ecef8ad9ca31a8372d0c353/pagerules/9a7806061c88ada191ed06f989cc3dac')
            );

        $pageRules = new \Cloudflare\API\Endpoints\PageRules($mock);
        $result = $pageRules->deletePageRule('023e105f4ecef8ad9ca31a8372d0c353', '9a7806061c88ada191ed06f989cc3dac');

        $this->assertTrue($result);
    }
}
