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

    public function resolveRoute($request)
    {
        $requestMethod = $request->getMethod();
        $requestPath = $request->getPath();
        $routeArray = $this->routeMap[$requestMethod][$requestPath] ?? null;
        $controllerClass = $routeArray['class'];
        $controllerMethod = $routeArray['method'];
        $controllerClassInstance = new $controllerClass();
        return $controllerClassInstance->{$controllerMethod}();
    }
}
