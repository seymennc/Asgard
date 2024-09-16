<?php

namespace Asgard\app\Controllers;

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
    public function post(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        return 'ok';
    }

}