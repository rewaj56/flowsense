<?php

namespace Rewaj56\Flowsense;

use Illuminate\Support\Facades\Route;

class FlowInfo
{
    public function getCurrentRouteInfo()
    {
        $route = Route::current();
        $routeUri = $route->uri();

        $action = $route->getActionName();
        // dd(explode('@', $action));
        $controller = 'No controller';
        $method = 'No method';

        if (strpos($action, '@') !== false) {
            list($controllerClass, $method) = explode('@', $action);
            // dd($controllerClass, $method);
            $controller = class_basename($controllerClass);
        } elseif ($action instanceof \Closure) {
            $controller = 'Closure';
        }

        return [
            'route' => $routeUri,
            'controller' => $controller,
            'method' => $method,
        ];
    }

    public function displayRouteInfo()
    {
        $info = $this->getCurrentRouteInfo();
        // dd($info);
        return "Route: {$info['route']} &nbsp;&nbsp;&nbsp; Controller: {$info['controller']} &nbsp;&nbsp;&nbsp; Method: {$info['method']}";
    }
}
