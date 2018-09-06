<?php

/**
 * User: junade
 * Date: 13/01/2017
 * Time: 23:35
 */

use GuzzleHttp\Psr7\Response;

class GuzzleTest extends TestCase
{
    private $client;

    public function setUp()
    {
        $auth = $this->getMockBuilder(\Cloudflare\API\Auth\Auth::class)
            ->setMethods(['getHeaders'])
            ->getMock();

        $auth->method('getHeaders')
            ->willReturn(['X-Testing' => 'Test']);

        $this->client = new \Cloudflare\API\Adapter\Guzzle($auth, 'https://httpbin.org/');
    }

    public function testGet()
    {
        $response = $this->client->get('https://httpbin.org/get');

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Test', $body->headers->{'X-Testing'});

        $response = $this->client->get('https://httpbin.org/get', [], ['X-Another-Test' => 'Test2']);
        $body = json_decode($response->getBody());
        $this->assertEquals('Test2', $body->headers->{'X-Another-Test'});
    }

    public function testPost()
    {
        $response = $this->client->post('https://httpbin.org/post', ['X-Post-Test' => 'Testing a POST request.']);

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Testing a POST request.', $body->json->{'X-Post-Test'});
    }

    public function testPut()
    {
        $response = $this->client->put('https://httpbin.org/put', ['X-Put-Test' => 'Testing a PUT request.']);

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Testing a PUT request.', $body->json->{'X-Put-Test'});
    }

    public function testPatch()
    {
        $response = $this->client->patch(
            'https://httpbin.org/patch',
            ['X-Patch-Test' => 'Testing a PATCH request.']
        );

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Testing a PATCH request.', $body->json->{'X-Patch-Test'});
    }

    public function testDelete()
    {
        $response = $this->client->delete(
            'https://httpbin.org/delete',
            ['X-Delete-Test' => 'Testing a DELETE request.']
        );

        $headers = $response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = json_decode($response->getBody());
        $this->assertEquals('Testing a DELETE request.', $body->json->{'X-Delete-Test'});
    }

    public function testErrors()
    {
        $class = new ReflectionClass(\Cloudflare\API\Adapter\Guzzle::class);
        $method = $class->getMethod('checkError');
        $method->setAccessible(true);

        $body =
            '{
                "result": null,
                "success": false,
                "errors": [{"code":1003,"message":"Invalid or missing zone id."}],
                "messages": []
             }'
        ;
        $response = new Response(200, [], $body);

        $this->expectException(\Cloudflare\API\Adapter\ResponseException::class);
        $method->invokeArgs($this->client, [$response]);

        $body =
            '{
                "result": null,
                "success": false,
                "errors": [],
                "messages": []
             }'
        ;
        $response = new Response(200, [], $body);

        $this->expectException(\Cloudflare\API\Adapter\ResponseException::class);
        $method->invokeArgs($this->client, [$response]);

        $body = 'this isnt json.';
        $response = new Response(200, [], $body);

        $this->expectException(\Cloudflare\API\Adapter\JSONException::class);
        $method->invokeArgs($this->client, [$response]);
    }

    public function testNotFound()
    {
        $this->expectException(\GuzzleHttp\Exception\RequestException::class);
        $this->client->get('https://httpbin.org/status/404');
    }
}
