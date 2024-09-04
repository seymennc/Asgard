<?php

namespace Asgard\App\Controllers;

use Asgard\database\models\UserModel;

class HomeController extends Controllers
{
    public function index()
    {
        $username = 'Seymen';
        //$user = Database::table('users')->get();
        //return $this->view('index', ['username' => $username, 'user' => $user]);
        $user = UserModel::getData();
        return $this->view('index', ['user' => $user]);
    }
    public function getUser($id, $id2, $id3)
    {
        return 'user id : ' . $id . ' user id 2 : ' . $id2 . ' user id 3: ' . $id3;
    }
}