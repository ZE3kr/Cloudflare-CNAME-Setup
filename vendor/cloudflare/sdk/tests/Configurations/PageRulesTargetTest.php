<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 18:41
 */

use Cloudflare\API\Configurations\PageRulesTargets;

class PageRulesTargetTest extends TestCase
{
    public function testGetArray()
    {
        $targets = new PageRulesTargets('junade.com/*');
        $array = $targets->getArray();

        $this->assertCount(1, $array);
        $this->assertEquals('junade.com/*', $array[0]['constraint']['value']);
        $this->assertEquals('matches', $array[0]['constraint']['operator']);
    }
}
