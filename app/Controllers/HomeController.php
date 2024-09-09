<?php

namespace Asgard\App\Controllers;

use Asgard\database\models\User;

class HomeController extends Controllers
{
    public function index()
    {
        $username = 'Seymen';
        //$user = Database::table('users')->get();
        //return $this->view('index', ['username' => $username, 'user' => $user]);
        $user = User::getData();
        return view('index', compact('user'));
    }
    public function getUser($id)
    {
        return 'user id : ' . $id;
    }
    public function admin()
    {
        return view('admin');
    }
    public function about()
    {
        return view('about');
    }
}