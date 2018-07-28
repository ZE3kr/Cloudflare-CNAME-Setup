<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 04/09/2017
 * Time: 20:08
 */

class NoneTest extends TestCase
{
    public function testGetHeaders()
    {
        $auth    = new \Cloudflare\API\Auth\None();
        $headers = $auth->getHeaders();

        $this->assertEquals([], $headers);
    }
}
