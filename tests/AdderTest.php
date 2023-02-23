<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    "vendor" .
    DIRECTORY_SEPARATOR .
    "autoload.php";

use PHPUnit\Framework\TestCase;
use Sandbox\Adder;

class AdderTest extends TestCase
{
    private Adder $adder;

    public function setUp(): void
    {
        $this->adder = new Adder();
    }

    public function tearDown(): void
    {
        unset($this->adder);
    }

    public function testBinaryAddition()
    {
        $a = 2;
        $b = 4;
        $expected = 6;
        $result = $this->adder->BinaryAddition($a, $b);
        $this->AssertEquals(
            $expected,
            $result,
            "The sum of 2 and 4 should be 6."
        );
    }
}
