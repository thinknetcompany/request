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
    public function testGet()
    {
        $baseUri = 'http://api.com/';
        $params  = ['params' => 'value'];
        $result  = ['result' => 'value'];
        $expectedResult  = json_decode(json_encode($result));
        $expectedOptions = ['query' => $params];

        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], json_encode($result)),
            new \GuzzleHttp\Exception\RequestException("Error Communicating with Server", new \GuzzleHttp\Psr7\Request('GET', 'test'))
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $service = new Thinknet\Request\Service($baseUri);
        $reflaction = new ReflectionClass($service);

        $reflectionClient = $reflaction->getProperty('client');
        $reflectionClient->setAccessible(true);
        $reflectionClient->setValue($service, $client);

        $reflectionOptions = $reflaction->getProperty('options');
        $reflectionOptions->setAccessible(true);

        $actualResult = $service->get('banners', $params)
                          ->getResult();

        $actualOptions = $reflectionOptions->getValue($service);

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertEquals($expectedOptions, $actualOptions);
    }
}