<?php
use Cloudflare\API\Configurations\PageRulesActions;

class PageRulesActionTest extends TestCase
{
    public function testForwardingURLConfigurationIsApplied()
    {
        $identifier = 'forwarding_url';
        $statusCode = 301;
        $forwardingURL = 'https://www.example.org/';

        $actions = new PageRulesActions();
        $actions->setForwardingURL($statusCode, $forwardingURL);
        $configuration = $actions->getArray();

        $this->assertCount(1, $configuration);
        $this->assertEquals($identifier, $configuration[0]['id']);
        $this->assertEquals($statusCode, $configuration[0]['value']['status_code']);
        $this->assertEquals($forwardingURL, $configuration[0]['value']['url']);
    }
}
