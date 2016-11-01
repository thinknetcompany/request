<?php
namespace Thinknet\Request;

use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    protected $defer = false;
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('\Thinknet\Request\Service', function () {
        	return new \Thinknet\Request\Service(env('SERVICE_BASE_URL', 'localhost'));
        });
    }

    public function provides()
    {
    	return ['\Thinknet\Request\Service'];
    }
}