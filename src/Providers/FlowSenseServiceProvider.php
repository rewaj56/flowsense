<?php

namespace Rewaj56\Flowsense\Providers;

use Illuminate\Support\ServiceProvider;

class FlowSenseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register the middleware
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', \Rewaj56\Flowsense\Http\Middleware\FlowSenseMiddleware::class);
    }

    public function register()
    {
        // Register any bindings or services
    }
}
