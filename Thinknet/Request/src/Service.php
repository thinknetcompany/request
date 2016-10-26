<?php
namespace Thinknet\Request;

class Service
{
    protected $response;
    protected $errorResponse;
    private $httpCode;
    protected $options = [];
    protected $client;

    public function __construct($baseUri)
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => $baseUri]);
    }

    public function get($url, $params = [])
    {
        $this->setParameters($params);
        $this->request($url, 'GET');

        return $this;
    }

    public function post($url, $params = [])
    {
        $this->setBody($params);
        $this->request($url, 'POST');

        return $this;
    }

    public function put($url, $params = [])
    {
        $this->setBody($params);
        $this->request($url, 'PUT');

        return $this;
    }

    public function delete($url, $params = [])
    {
        $this->setBody($params);
        $this->request($url, 'DELETE');

        return $this;
    }

    protected function request($url, $method)
    {
        try {
            $this->response = $this->client->request($method, $url, $this->options);
            $this->errorResponse = null;
            $this->httpCode = $this->response->getStatusCode();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->response = null;
            $this->errorResponse = $e->getResponse();
            $this->httpCode = $this->errorResponse->getStatusCode();
        }

        return $this;
    }

    public function basicAuth($username, $password)
    {
        $this->options['auth'] = [$username, $password];
    }

    public function setHeaders(array $headers)
    {
        $this->options['headers'] = $headers;
    }

    public function addHeader($key, $value)
    {
        if (!isset($this->options['headers']) || !is_array($this->options['headers'])) {
            $this->options['headers'] = [];
        }

        $this->options['headers'][$key] = $value;
    }

    protected function setParameters(array $params)
    {
        $this->options['query'] = $params;
    }

    protected function setBody(array $params)
    {
        $this->options['form_params'] = $params;
    }

    public function getResult()
    {
        return json_decode($this->response->getBody());
    }

    public function getError()
    {
        return json_decode($this->errorResponse->getBody());
    }

    public function isSuccess()
    {
        return preg_match('/^2|^3/', $this->getHttpCode()) ? true : false;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}