<?php

namespace Rewaj56\Flowsense\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Rewaj56\Flowsense\Collectors\QueryCollector;
use Rewaj56\Flowsense\Collectors\ViewCollector;
use Rewaj56\Flowsense\Http\Middleware\FlowSenseMiddleware;

class FlowSenseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'flowsense');
        $this->publishes([
            __DIR__ . '/../../resources/js' => public_path('vendor/flowsense/js'),
            __DIR__ . '/../../resources/css' => public_path('vendor/flowsense/css'),
        ], 'flowsense-assets');
        
        View::composer('*', function ($view) {
            ViewCollector::add([
                'name' => $view->getName(),
                'path' => $view->getPath(),
                'data' => $view->getData(),
            ]);
        });

        if (config('app.debug')) {
            QueryCollector::listen();

            DB::listen(function ($query) {
                QueryCollector::add([
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time / 1000,
                ]);
            });
        }

        $this
            ->app['router']
            ->pushMiddlewareToGroup('web', FlowSenseMiddleware::class);
    }

    public function register()
    {
        //
    }
}
