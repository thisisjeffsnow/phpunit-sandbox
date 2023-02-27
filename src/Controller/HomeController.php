<?php

namespace Sandbox\Controller;
use Sandbox\Request;
use Sandbox\View\View;

class HomeController
{
    public function getMain(Request $request)
    {
        $view = new View($request);
        return $view->render();
    }
}
