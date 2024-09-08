<?php

namespace Rewaj56\Flowsense\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Rewaj56\Flowsense\FlowInfo;
use Rewaj56\Flowsense\Services\FlowSenseService;

class FlowSenseMiddleware
{
    protected $flowSenseService;

    public function __construct(FlowSenseService $flowSenseService)
    {
        $this->flowSenseService = $flowSenseService;
    }

    public function handle($request, Closure $next)
    {
        DB::enableQueryLog();
        View::composer('*', function ($view) {
            $this->flowSenseService->addViewPath($view->getPath());
        });

        $startTime = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);

        $responseTime = number_format($endTime - $startTime, 4);
        $totalQueryDuration = $this->getTotalQueryDuration();

        $routeInfo = $this->flowSenseService->getRouteInfo();
        $viewPathsHtml = $this->flowSenseService->formatViewPaths($routeInfo['view_paths']);

        $content = $response->getContent();
        $content .= $this->generateHtml($routeInfo, $responseTime, $totalQueryDuration, $viewPathsHtml);
        $response->setContent($content);

        return $response;
    }

    protected function getTotalQueryDuration()
    {
        $queries = DB::getQueryLog();
        return array_sum(array_column($queries, 'time'));
    }

    protected function generateHtml($routeInfo, $responseTime, $totalQueryDuration, $viewPathsHtml)
    {
        return <<<HTML
        <style>
            #flowSenseBtn {
                position: fixed;
                bottom: 20px;
                left: 20px;
                background-color: #f05340;
                font-weight: bold;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 50%;
                font-size: 16px;
                cursor: pointer;
                z-index: 1000;
            }
            #flowSenseInfo {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #333;
                color: white;
                padding: 20px;
                border-top: 3px solid #f05340;
                box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
                display: none;
                z-index: 999;
                box-sizing: border-box;
            }
            #flowSenseInfo > div {
                display: flex;
                flex-wrap: wrap;
            }
            #flowSenseInfo .column {
                flex: 1;
                min-width: 200px;
                padding: 10px;
                box-sizing: border-box;
            }
            #flowSenseInfo .column:first-child {
                background-color: #444;
                margin-left: 3rem;
            }
            #flowSenseInfo .column:last-child {
                background-color: #494949  ;
            }
            #flowSenseInfo > p {
                margin: 0.5rem 0;
            }
        </style>

        <a id="flowSenseBtn">fs</a>

        <div id="flowSenseInfo">
            <h4><i>flowsense</i></h4>
            <div>
                <div class="column">
                    <p><strong>Route:</strong> {$routeInfo['route']}</p>
                    <p><strong>Controller:</strong> {$routeInfo['controller']}</p>
                    <p><strong>Method:</strong> {$routeInfo['method']}</p>
                    <p><strong>Response Time:</strong> {$responseTime} seconds</p>
                    <p><strong>Total Query Time:</strong> {$totalQueryDuration} seconds</p>
                </div>
                <div class="column">
                    <p><strong>View Paths:</strong></p>
                    <ul>
                        {$viewPathsHtml}
                    </ul>
                </div>
            </div>
        </div>

        <script>
            var flowSenseBtn = document.getElementById('flowSenseBtn');
            var flowSenseInfo = document.getElementById('flowSenseInfo');

            flowSenseBtn.addEventListener('click', function() {
                if (flowSenseInfo.style.display === 'block') {
                    flowSenseInfo.style.display = 'none';
                    flowSenseBtn.innerHTML = 'fs';
                } else {
                    flowSenseInfo.style.display = 'block';
                    flowSenseBtn.innerHTML = 'X';
                }
            });
        </script>
        HTML;
    }
}
