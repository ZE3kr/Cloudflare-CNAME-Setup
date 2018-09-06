<?php
/**
 * User: junade
 * Date: 13/01/2017
 * Time: 18:26
 */

namespace Cloudflare\API\Adapter;

use Cloudflare\API\Auth\Auth;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Guzzle implements Adapter
{
    private $client;

    /**
     * @inheritDoc
     */
    public function __construct(Auth $auth, string $baseURI = null)
    {
        if ($baseURI === null) {
            $baseURI = 'https://api.cloudflare.com/client/v4/';
        }

        $headers = $auth->getHeaders();

        $this->client = new Client([
            'base_uri' => $baseURI,
            'headers' => $headers,
            'Accept' => 'application/json'
        ]);
    }


    /**
     * @inheritDoc
     */
    public function get(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('get', $uri, $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('post', $uri, $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function put(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('put', $uri, $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('patch', $uri, $data, $headers);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        return $this->request('delete', $uri, $data, $headers);
    }

    public function request(string $method, string $uri, array $data = [], array $headers = [])
    {
        if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
            throw new \InvalidArgumentException('Request method must be get, post, put, patch, or delete');
        }

        $response = $this->client->$method($uri, [
            'headers' => $headers,
            ($method === 'get' ? 'query' : 'json') => $data,
        ]);

        $this->checkError($response);

        return $response;
    }

    private function checkError(ResponseInterface $response)
    {
        $json = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JSONException();
        }

        if (isset($json->errors) && count($json->errors) >= 1) {
            throw new ResponseException($json->errors[0]->message, $json->errors[0]->code);
        }

        if (isset($json->success) && !$json->success) {
            throw new ResponseException('Request was unsuccessful.');
        }
    }
}
