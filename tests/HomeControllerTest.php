<?php

namespace Sandbox\Test;

require dirname(dirname(__FILE__)) .
    DIRECTORY_SEPARATOR .
    'vendor' .
    DIRECTORY_SEPARATOR .
    'autoload.php';

use PHPUnit\Framework\TestCase;
use Sandbox\Controller\HomeController;

class HomeControllerTest extends TestCase
{
    public HomeController $homeController;

    public function setUp(): void
    {
        $this->homeController = new HomeController();
    }

    public function tearDown(): void
    {
        unset($this->homeController);
    }

    public function testHomeControllerGetMain()
    {
        /*
         * The getMain method should display the default view
         * with the default content of the main homepage.
         * Test that getMain does echo something that contains HTML?
         */

        ob_start();
        $this->homeController->getMain();
        $output = ob_get_clean();

        $this->assertStringContainsString('<html>', $output);
    }
}
