<?php

namespace Rewaj56\Flowsense\Http\Middleware;

use Closure;
use Rewaj56\Flowsense\FlowInfo;

class FlowSenseMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $flowInfo = new FlowInfo();
        $routeInfo = $flowInfo->displayRouteInfo();

        $content = $response->getContent();

        $content .= <<<HTML
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
            }
            #flowSenseInfo > p {
                margin-left: 5rem;
            }
        </style>

        <a id="flowSenseBtn">fs</a>

        <div id="flowSenseInfo">
            <h4><i>flowsense</i></h4>
            <p>{$routeInfo}</p>
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

        $response->setContent($content);
        return $response;
    }
}
