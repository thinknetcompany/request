<?php
require_once('Thinknet/Request/src/Service.php');
require_once('vendor/autoload.php');
require_once('vendor/guzzlehttp/guzzle/src/Client.php');
require_once('vendor/guzzlehttp/psr7/src/Response.php');
require_once('vendor/guzzlehttp/psr7/src/Request.php');
require_once('vendor/guzzlehttp/guzzle/src/Handler/MockHandler.php');
require_once('vendor/guzzlehttp/guzzle/src/HandlerStack.php');
require_once('vendor/guzzlehttp/guzzle/src/Exception/ClientException.php');

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use Thinknet\Request\Service;

/**
 * @covers Thinknet\Request\Service
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    protected $service;
    protected $body;

    public function setup()
    {
        $baseUri    = 'http://example.com/';
        $result     = ['foo' => 'bar'];
        $this->body = json_decode(json_encode($result));

        $mock = new MockHandler([
            new Response(200, [], json_encode($result))
        ]);
        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler' => $handler]);

        $this->service = new Service($baseUri);
        $reflaction    = new ReflectionClass($this->service);
        $reflectionClient = $reflaction->getProperty('client');
        $reflectionClient->setAccessible(true);
        $reflectionClient->setValue($this->service, $client);
    }

    public function testGet()
    {
        $this->service->get('foo');

        $this->assertEquals($this->body, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
        $this->assertEquals(true, $this->service->isSuccess());
    }

    public function testPost()
    {
        $this->service->post('foo');

        $this->assertEquals($this->body, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
        $this->assertEquals(true, $this->service->isSuccess());
    }

    public function testPut()
    {
        $this->service->put('foo');

        $this->assertEquals($this->body, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
        $this->assertEquals(true, $this->service->isSuccess());
    }

    public function testDelete()
    {
        $this->service->delete('foo');

        $this->assertEquals($this->body, $this->service->getResult());
        $this->assertEquals(200, $this->service->getHttpCode());
        $this->assertEquals(true, $this->service->isSuccess());
    }

    public function testRequestError()
    {
        $baseUri  = 'http://example.com/';
        $response = ['code' => 405, 'message' => 'Method not Allowed.'];
        $mock = new MockHandler([
            new Response(405, [], json_encode($response))
        ]);
        $handler = HandlerStack::create($mock);
        $client  = new Client(['handler' => $handler]);

        $service    = new Service($baseUri);
        $reflaction = new ReflectionClass($service);
        $reflectionClient = $reflaction->getProperty('client');
        $reflectionClient->setAccessible(true);
        $reflectionClient->setValue($service, $client);

        $service->get('foo');

        $this->assertEquals(json_decode(json_encode($response)), $service->getError());
        $this->assertEquals(405, $service->getHttpCode());
        $this->assertEquals(false, $service->isSuccess());
    }
}