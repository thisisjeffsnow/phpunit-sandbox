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

    public function resolveRoute(RequestInterface $request)
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getPath();
        $routeArray = $this->routeMap[$requestMethod][$requestPath] ?? [
            'class' => 'Sandbox\HomeController',
            'method' => 'getHome',
        ];
        $controllerClass = $routeArray['class'];
        $controllerMethod = $routeArray['method'];
        $controllerClassInstance = new $controllerClass();
        return $controllerClassInstance->{$controllerMethod}();
    }
}
