<?php

namespace Sandbox;
use Sandbox\Exception\InvalidRequestMethodException;

class Request
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';
    public const METHOD_PUT = 'PUT';

    public function getPath()
    {
        $rawPath = $_SERVER['REQUEST_URI'];
        if ($rawPath == '') {
            return '/';
        }
        $delimPos = strpos($rawPath, '?');
        return $delimPos === false ? $rawPath : substr($rawPath, 0, $delimPos);
    }

    public function getMethod()
    {
        $rawMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        switch ($rawMethod) {
            case Request::METHOD_POST:
            case Request::METHOD_GET:
            case Request::METHOD_PUT:
                return $rawMethod;
                break;
            default:
                throw new InvalidRequestMethodException(
                    'HTTP Request method must be GET, POST, or PUT. '
                );
        }
    }
}
