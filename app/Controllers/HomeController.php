<?php

namespace Asgard\app\Controllers;

use Asgard\database\models\User;

class HomeController extends Controllers
{
    public function index()
    {
        return view('index');
    }

    public function about()
    {
        return 'About controller noktası';
    }
    public function post()
    {
        return 'ok';
    }

}