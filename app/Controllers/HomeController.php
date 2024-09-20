<?php

namespace Asgard\app\Controllers;

use App\Requests\TestRequest;
use Asgard\App\Helpers\Redirect;
use Asgard\database\models\User;
use Asgard\system\Request;

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
    public function post(TestRequest $request)
    {
        return 'ok';
    }

}