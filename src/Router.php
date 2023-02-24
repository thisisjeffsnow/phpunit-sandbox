<?php

namespace Sandbox;
use Sandbox\Request;

class Router
{
    public function __construct(public array $routeMap = [])
    {
    }

    public function addRoute(
        $requestMethod,
        $requestPath,
        $controllerClass,
        $controllerMethod
    ) {
        $this->routeMap[$requestMethod][$requestPath] = [
            'class' => $controllerClass,
            'method' => $controllerMethod,
        ];
    }

    public function resolveRoute(Request $request)
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getPath();
        $requestedRouteMapArray = $this->routeMap[$requestMethod][$requestPath];
        $controllerClass = $requestedRouteMapArray['class'];
        $controllerMethod = $requestedRouteMapArray['method'];
        return [$controllerClass, $controllerMethod];
    }
}
