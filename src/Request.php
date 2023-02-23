<?php

namespace Sandbox;

class Request
{
    public function getPath()
    {
        $rawPath = $_SERVER["REQUEST_URI"];
        if (strlen($rawPath) < 1) {
            return "/";
        }
        $delimPos = strpos($rawPath, "?");
        return $delimPos === false ? $rawPath : substr($rawPath, 0, $delimPos);
    }
}
