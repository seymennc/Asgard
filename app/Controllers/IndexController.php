<?php

namespace Asgard\app\Controllers;


use Asgard\system\Request;

class IndexController extends Controllers
{

    public function index(): string
    {
        return view('asgard');
    }


    public function post(Request $request): string
    {
        return $request->all();
    }
}
