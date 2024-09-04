<?php

namespace Asgard\app\Controllers;

use Asgard\system\Blade;
use Asgard\system\View;

class Controllers
{
    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function view(string $name, array $params = []): string
    {
        $blade = new Blade();

        return $blade->view($name, $params);
        //return View::show($name, $params);
    }
}