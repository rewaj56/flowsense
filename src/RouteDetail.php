<?php

namespace Rewaj56\Flowsense;

use Illuminate\Support\Facades\Route;

class RouteDetail
{
    public function collect(): array
    {
        $route = Route::current();

        if (!$route) {
            return [
                'route' => null,
                'controller' => null,
                'method' => null,
                'name' => null,
                'middleware' => [],
                'params' => [],
            ];
        }

        $action = $route->getActionName();
        $controller = 'Closure';
        $method = null;

        if (is_string($action) && str_contains($action, '@')) {
            [$controllerClass, $method] = explode('@', $action);
            $controller = class_basename($controllerClass);
        }

        return [
            'route' => $route->uri(),
            'controller' => $controller,
            'method' => $method,
            'name' => $route->getName(),
            'middleware' => $route->gatherMiddleware(),
            'params' => $route->parameters(),
        ];
    }
}