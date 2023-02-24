<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\Request;

class RequestTest extends TestCase
{
    private Request $request;

    public function setUp(): void
    {
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        unset($this->request);
        unset($_SERVER['REQUEST_URI']);
    }

    /**
     * @dataProvider requestGetPathProvider
     */
    public function testRequestGetPath($givenPath, $expectedResult)
    {
        $_SERVER['REQUEST_URI'] = "$givenPath";

        $actualResult = $this->request->getPath();

        $this->assertEquals(
            $expectedResult,
            $actualResult,
            "When the Request class runs getPath on '$givenPath', " .
                "it should return '$expectedResult'."
        );
    }

    public static function requestGetPathProvider()
    {
        return [
            'query variable' => ['/test/path.php?id=1', '/test/path.php'],
            'slash' => ['/', '/'],
            'empty' => ['', '/'], # not sure if this even ever happens?
            'simple file' => ['/test.php', '/test.php'],
        ];
    }

    /**
     * @dataProvider requestGetMethodProvider
     */
    public function testRequestGetMethod($givenRequestMethod, $expectedResult)
    {
        $_SERVER['REQUEST_METHOD'] = "$givenRequestMethod";

        $actualResult = $this->request->getMethod();

        $this->assertEquals(
            $expectedResult,
            $actualResult,
            "When the Request class runs getMethod on '$givenRequestMethod', " .
                "it should return '$expectedResult'."
        );
    }

    public static function requestGetMethodProvider()
    {
        return [
            'Test for method GET' => ['GET', 'GET'],
            'Test for method POST' => ['POST', 'POST'],
            'lowercase get' => ['get', 'GET'],
            'lowercase post' => ['post', 'POST'],
        ];
    }
}
