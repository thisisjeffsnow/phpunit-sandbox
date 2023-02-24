<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\Router;

class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function tearDown(): void
    {
        unset($this->router);
    }

    public function testAddRoute(
        $givenRequestMethod,
        $givenRequestPath,
        $givenControllerClass,
        $givenControllerMethod,
        $expectedResult
    ) {
        $this->router->addRoute(
            $givenRequestMethod,
            $givenRequestPath,
            $givenControllerClass,
            $givenControllerMethod
        );
        $this->assertEquals($expectedResult, $this->router->routeMap);
    }
}
