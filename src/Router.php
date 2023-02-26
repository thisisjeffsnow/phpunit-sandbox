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
        if (!$routeArray) {
            throw new RouteArrayNotFoundException(
                'Requested Route for Request Method ' .
                    'and Request Path not found in RouteMap.'
            );
        }

        $controllerClass = $routeArray['class'];
        $controllerMethod = $routeArray['method'];

        if (!class_exists($controllerClass)) {
            throw new RouteClassNotFoundException(
                'Requested Class for Requested Route not found ' .
                    'or not instantiated.'
            );
        }
        $controllerClassInstance = new $controllerClass();

        if (!method_exists($controllerClass, $controllerMethod)) {
            throw new RouteMethodNotFoundException(
                'Requested Method for Requested Route Class not found ' .
                    'or not instantiated.'
            );
        }
        return $controllerClassInstance->{$controllerMethod}();
    }
}
