Thinknet Service For Laravel
========

php http request for laravel

Support :

- Service HTTP Method GET, POST, PUT, DELETE

## Requirement
* PHP 5.6.0
* Laravel 5.3
* guzzle 6.0

## Composer

This plugin on the Packagist.

[https://packagist.org/packages/thinknet/request](https://packagist.org/packages/thinknet/request)

Install the latest version with composer require thinknet/request


To get the lastest version of Theme simply require it in your composer.json file.

```php

    "thinknet/request": "dev-master"

```

You'll then need to run composer install to download it and have the autoloader updated.


Once Theme is installed you need to register the service provider with the application. Open up config/app.php and find the providers key.

```php

'providers' => array(

    'Thinknet\Request\RequestServiceProvider'

)

```
You can register the facade in the aliases key of your config/app.php file.

```php

'aliases' => array(

    'ThinknetService' => 'Thinknet\Request\Service'

)

```

You can set Url on .env file

```php

SERVICE_BASE_URL=http://www.example.com

```

## Usage

API ThinknetService Requests format.

```php

use ThinknetService;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}

```

Main Method

```php

// get : Return this
public function getService()
{
    $this->service->get(url, param);
}

// post : Return this
public function postService()
{
    $this->service->post(url, param);
}

// put : Return this
public function putService()
{
    $this->service->put(url, param);
}

// delete : Return this
public function deleteService()
{
    $this->service->delete(url, param);
}

```

Other Method

```php

// getResult : Return Json Obj OR Null
    $this->service->getResult();

// call : Return this
    $this->service->call($url, $method)

// isSuccess : Return Boolean
    $this->service->isSuccess();

// getHttpCode : Return int
    $this->service->getHttpCode();

// setHeader : Return Void
    $this->service->setHeader(key, value);

// addHeader : Return Void
    $this->service->SetHeader(array $params);

// setParameters : Return Void
    $this->service->setParameters(array $params);
    
// setbody : Return Void
    $this->service->setBody(params)
    
// basicAuth : Return Void
    $this->service->basicAuth(username, password);

// getError : Return Json Obj
    $this->service->getError();

```

License
=======
The MIT License (MIT)
