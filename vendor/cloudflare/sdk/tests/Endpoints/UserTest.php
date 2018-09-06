<?php

/**
 * User: junade
 * Date: 01/02/2017
 * Time: 12:50
 */
class UserTest extends TestCase
{
    public function testGetUserDetails()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getUserDetails.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $user = new \Cloudflare\API\Endpoints\User($mock);
        $details = $user->getUserDetails();

        $this->assertObjectHasAttribute('id', $details);
        $this->assertEquals('7c5dae5552338874e5053f2534d2767a', $details->id);
        $this->assertObjectHasAttribute('email', $details);
        $this->assertEquals('user@example.com', $details->email);
    }

    public function testGetUserID()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getUserId.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $user = new \Cloudflare\API\Endpoints\User($mock);
        $this->assertEquals('7c5dae5552338874e5053f2534d2767a', $user->getUserID());
    }

    public function testGetUserEmail()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/getUserEmail.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('get')->willReturn($response);

        $mock->expects($this->once())->method('get');

        $user = new \Cloudflare\API\Endpoints\User($mock);
        $this->assertEquals('user@example.com', $user->getUserEmail());
    }

    public function testUpdateUserDetails()
    {
        $response = $this->getPsr7JsonResponseForFixture('Endpoints/updateUserDetails.json');

        $mock = $this->getMockBuilder(\Cloudflare\API\Adapter\Adapter::class)->getMock();
        $mock->method('patch')->willReturn($response);

        $mock->expects($this->once())
            ->method('patch')
            ->with($this->equalTo('user'), $this->equalTo(['email' => 'user2@example.com']));

        $user = new \Cloudflare\API\Endpoints\User($mock);
        $user->updateUserDetails(['email' => 'user2@example.com']);
    }
}
