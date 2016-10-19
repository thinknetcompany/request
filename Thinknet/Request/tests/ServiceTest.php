<?php
require_once('Service.php');
class ServiceTest extends PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        echo "test";
        $service = new App\Classes\Request\Service();

        echo "<pre>";
        print_r($service);
        echo "</pre>";

    }
}