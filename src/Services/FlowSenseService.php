<?php

namespace Rewaj56\Flowsense\Services;

use Rewaj56\Flowsense\FlowInfo;

class FlowSenseService
{
    protected $flowInfo;

    public function __construct(FlowInfo $flowInfo)
    {
        $this->flowInfo = $flowInfo;
    }

    public function addViewPath($path)
    {
        $this->flowInfo::addViewPath($path);
    }

    public function getRouteInfo()
    {
        return $this->flowInfo->displayRouteInfo();
    }

    public function formatViewPaths($viewPaths)
    {
        $viewPathsHtml = '';
        foreach ($viewPaths as $viewPath) {
            if (strpos($viewPath, '\resources\views') !== false) {
                $basePath = '\\resources\\views';
                $pos = strpos($viewPath, $basePath);
                if ($pos !== false) {
                    $trimmedPath = substr($viewPath, $pos);
                    $viewPathsHtml .= '<li>' . htmlspecialchars($trimmedPath, ENT_QUOTES, 'UTF-8') . '</li>';
                }
            }
        }
        return $viewPathsHtml;
    }
}
