<?php

namespace Sandbox;

class Request
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function getPath()
    {
        $rawPath = $_SERVER['REQUEST_URI'];
        if (strlen($rawPath) < 1) {
            return '/';
        }
        $delimPos = strpos($rawPath, '?');
        return $delimPos === false ? $rawPath : substr($rawPath, 0, $delimPos);
    }

    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
}
