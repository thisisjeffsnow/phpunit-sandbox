<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\RequestInterface;
use Sandbox\Router;
use Sandbox\Request;

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

    /**
     * @dataProvider addRouteProvider
     */
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

    public static function addRouteProvider()
    {
        return [
            [
                Request::METHOD_GET,
                '/',
                'Sandbox\HomeController',
                'getHome',
                [
                    Request::METHOD_GET => [
                        '/' => [
                            'class' => 'Sandbox\HomeController',
                            'method' => 'getHome',
                        ],
                    ],
                ],
            ],
            [
                Request::METHOD_POST,
                '/',
                'Sandbox\HomeController',
                'postHome',
                [
                    Request::METHOD_POST => [
                        '/' => [
                            'class' => 'Sandbox\HomeController',
                            'method' => 'postHome',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider resolveRouteProvider
     */
    public function testResolveRoute(
        $givenRequestMethod,
        $givenRequestPath,
        $givenControllerClass,
        $givenControllerMethod
    ) {
        $this->router->routeMap[$givenRequestMethod][$givenRequestPath] = [
            'class' => $givenControllerClass,
            'method' => $givenControllerMethod,
        ];

        $givenClassInstance = new $givenControllerClass();
        $expectedResult = $givenClassInstance->{$givenControllerMethod}();

        $requestMock = $this->getMockBuilder(
            RequestInterface::class
        )->getMock();
        $this->assertInstanceOf(RequestInterface::class, $requestMock);

        $requestMock
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn($givenRequestMethod);
        $requestMock
            ->expects($this->once())
            ->method('getPath')
            ->willReturn($givenControllerMethod);

        $actualResult = $this->router->resolveRoute($requestMock);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public static function resolveRouteProvider()
    {
        return [
            'test home get' => [
                Request::METHOD_GET,
                '/',
                'Sandbox\HomeController',
                'getHome',
            ],
            'test home post' => [
                Request::METHOD_POST,
                '/',
                'Sandbox\HomeController',
                'postHome',
            ],
        ];
    }
}
