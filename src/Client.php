<?php

namespace Lucentcms\Lucentcms;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    const API_URL = "https://api.lucentcms.com/api/";
    protected string $channel;
    protected string $token;
    protected ?string $user = null;
    protected $guzzle;
    protected $errors;
    protected $headers = [];
    protected $body;
    protected $withExceptions = false;

    function __construct(
        $channel,
        $token,
        $user = null
    ) {
        $this->channel = $channel;
        $this->token = $token;
        $this->user = $user;

        $this->headers = [
            'Accept' => 'application/json',
            'Lucent-Channel' => $this->channel,
            'Authorization' => 'Bearer ' . $this->token,
        ];

        if (is_null($this->user) !== true) {
            $this->headers['Lucent-User'] = $this->user;
        }

        $this->guzzle = new GuzzleClient([
            'base_uri' => self::API_URL,
            'http_errors' => false
        ]);
    }

    public function baseRequest(string $method, string $endpoint, array $data = [])
    {
        $payload = [
            'headers' => $this->headers
        ];
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $payload['json'] = $data;
            $payload['headers']['Content-Type'] = 'application/json';
        }

        if (in_array($method, ['GET', 'DELETE'])) {
            $payload['query'] = $data;
            $payload['headers']['Content-Type'] = 'application/json';
        }

        if (in_array($method, ['UPLOAD'])) {
            $payload['multipart'] = $data;
            $method = 'POST';
        }

        $response = $this->guzzle->request($method, $endpoint, $payload);

        $body = json_decode((string)$response->getBody(), true);

        $this->code = $response->getStatusCode();
        $this->body = $body;
        if (isset($body['errors'])) {
            $this->errors = $body['errors'];
        }

        // if ($this->hasErrors() && $this->withExceptions) {
        //     $this->throwException();
        // }

        return $this;
    }

    public function get($endpoint, $params = [])
    {
        return  $this->baseRequest('GET', $endpoint, $params);
    }


    public function post($endpoint, $data = [])
    {
        return  $this->baseRequest('POST', $endpoint, $data);
    }

    public function put($endpoint, $data)
    {
        return $this->baseRequest('PUT', $endpoint, $data);
    }

    public function patch($endpoint, $data)
    {
        return $this->baseRequest('PATCH', $endpoint, $data);
    }


    public function delete($endpoint, $params = [])
    {
        return $this->baseRequest('DELETE', $endpoint, $params);
    }

    public function upload($endpoint, $data = [])
    {
        return  $this->baseRequest('UPLOAD', $endpoint, $data);
    }

    public function addHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function data()
    {
        return $this->body['data'];
    }

    public function included()
    {
        return $this->body['included'];
    }

    public function first()
    {
        return $this->body['data'][0] ?? null;
    }

    public function body()
    {
        return $this->body;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }
    public function errors()
    {
        return $this->errors;
    }

    // public function throwException()
    // {
    //     new (400, $this->error());
    // }

    // public function withExceptions()
    // {
    //     $this->withExceptions = true;
    //     return $this;
    // }
}
