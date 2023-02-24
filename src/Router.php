<?php

namespace Sandbox;

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
}
