<?php

/**
 * User: junade
 * Date: 13/01/2017
 * Time: 18:03
 */
class UserServiceKeyTest extends TestCase
{
    public function testGetHeaders()
    {
        $auth    = new \Cloudflare\API\Auth\UserServiceKey('v1.0-e24fd090c02efcfecb4de8f4ff246fd5c75b48946fdf0ce26c59f91d0d90797b-cfa33fe60e8e34073c149323454383fc9005d25c9b4c502c2f063457ef65322eade065975001a0b4b4c591c5e1bd36a6e8f7e2d4fa8a9ec01c64c041e99530c2-07b9efe0acd78c82c8d9c690aacb8656d81c369246d7f996a205fe3c18e9254a');
        $headers = $auth->getHeaders();

        $this->assertArrayHasKey('X-Auth-User-Service-Key', $headers);

        $this->assertEquals(
            'v1.0-e24fd090c02efcfecb4de8f4ff246fd5c75b48946fdf0ce26c59f91d0d90797b-cfa33fe60e8e34073c149323454383fc9005d25c9b4c502c2f063457ef65322eade065975001a0b4b4c591c5e1bd36a6e8f7e2d4fa8a9ec01c64c041e99530c2-07b9efe0acd78c82c8d9c690aacb8656d81c369246d7f996a205fe3c18e9254a',
            $headers['X-Auth-User-Service-Key']
        );

        $this->assertCount(1, $headers);
    }
}
