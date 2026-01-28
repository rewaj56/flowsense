<?php

namespace Rewaj56\Flowsense\Http\Middleware;

use Rewaj56\Flowsense\Collectors\QueryCollector;
use Rewaj56\Flowsense\Collectors\ViewCollector;
use Rewaj56\Flowsense\RouteDetail;
use Closure;

class FlowSenseMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        $responseTime = microtime(true) - $startTime;

        if (!config('app.debug')) {
            return $response;
        }

        if ($request->expectsJson()) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type');
        if (!$contentType || !str_contains($contentType, 'text/html')) {
            return $response;
        }

        $routeDetail = new RouteDetail();
        $route = $routeDetail->collect();
        $queries = QueryCollector::collect();

        $performance = [
            'response_time' => number_format($responseTime, 4),
        ];

        $view = ViewCollector::getAll();

        $content = $response->getContent();
        $content .= view('flowsense::bar', compact(
            'route',
            'queries',
            'performance',
            'view',
        ))->render();

        $response->setContent($content);
        return $response;
    }
}
