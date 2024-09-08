<?php

namespace Rewaj56\Flowsense;

use Illuminate\Support\Facades\Route;

class FlowInfo
{
    protected static $viewPaths = [];

    public static function addViewPath($path)
    {
        self::$viewPaths[] = $path;
    }

    public static function getViewPaths()
    {
        return self::$viewPaths;
    }

    public function getCurrentRouteInfo()
    {
        $route = Route::current();
        $routeUri = $route->uri();
        $action = $route->getActionName();
        $controller = 'No controller';
        $method = 'No method';

        if (strpos($action, '@') !== false) {
            list($controllerClass, $method) = explode('@', $action);
            $controller = class_basename($controllerClass);
        } elseif ($action instanceof \Closure) {
            $controller = 'Closure';
        }

        return [
            'route' => $routeUri,
            'controller' => $controller,
            'method' => $method,
            'view_paths' => self::getViewPaths(),
        ];
    }

    public function displayRouteInfo()
    {
        return $this->getCurrentRouteInfo();
    }
}
