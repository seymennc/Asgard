<?php

require __DIR__ . '/vendor/autoload.php';

use Asgard\Core\Route;
use Dotenv\Dotenv;


$app = new \Asgard\Core\App();

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/user/:id1/:id2/:id3', 'HomeController@getUser')->name('user');

Route::url('user', ['id' => 3]);

Route::prefix('/admin?')->group(function () {
    Route::get('/', 'HomeController@index')->name('admin');
    Route::get('/settings', 'HomeController@index')->name('admin.settings');
});

Route::get('/about', function () {
    return 'This is a about page';
});

Route::post('/updateUser', function () {
    return 'This is a update page';
});

Route::dispatch();