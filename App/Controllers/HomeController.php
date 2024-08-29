<?php

namespace Asgard\App\Controllers;

class HomeController
{
    public function index()
    {
        return 'This is a main page';
    }
    public function getUser($id, $id2, $id3)
    {
        return 'user id : ' . $id . ' user id 2 : ' . $id2 . ' user id 3: ' . $id3;
    }
}