<?php

namespace Sandbox;

interface RequestInterface
{
    public function getPath();
    public function getMethod();
}
