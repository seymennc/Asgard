<?php

namespace Asgard\app\Controllers;

use Asgard\database\models\User;

class HomeController extends Controllers
{
    public function index()
    {
        return 'Index controller noktası';
    }

    public function about()
    {
        return 'About controller noktası';
    }

}