<?php
require_once('Thinknet/Request/src/Service.php');
require_once('vendor/autoload.php');
require_once('vendor/guzzlehttp/guzzle/src/Client.php');
require_once('vendor/guzzlehttp/psr7/src/Response.php');
require_once('vendor/guzzlehttp/psr7/src/Request.php');
require_once('vendor/guzzlehttp/guzzle/src/Handler/MockHandler.php');
require_once('vendor/guzzlehttp/guzzle/src/HandlerStack.php');
require_once('vendor/guzzlehttp/guzzle/src/Exception/RequestException.php');

class ServiceTest extends PHPUnit_Framework_TestCase
{
    protected $service;
    protected $expectedBody;

    public function setup() {
        $baseUri = 'http://api.com/';
        $result  = ['foo' => 'bar'];
        $this->expectedBody = json_decode(json_encode($result));

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($result)),
            new \GuzzleHttp\Exception\RequestException("Error Communicating with Server", new \GuzzleHttp\Psr7\Request('GET', 'test'))
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client  = new \GuzzleHttp\Client(['handler' => $handler]);

        $this->service = new Thinknet\Request\Service($baseUri);
        $reflaction    = new ReflectionClass($this->service);

        $reflectionClient = $reflaction->getProperty('client');
        $reflectionClient->setAccessible(true);
        $reflectionClient->setValue($this->service, $client);
    }

    public function testGet()
    {
        $this->service->get('foo');

        $this->assertEquals($this->expectedBody, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
    }

    public function testPost()
    {
        $this->service->post('foo');

        $this->assertEquals($this->expectedBody, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
    }

    public function testPut()
    {
        $this->service->put('foo');

        $this->assertEquals($this->expectedBody, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
    }

    public function testDelete()
    {
        $this->service->delete('foo');

        $this->assertEquals($this->expectedBody, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
    }
}