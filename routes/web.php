<?php

use Asgard\system\Route;
use Asgard\system\View;

Route::get('/', 'HomeController@index')->name('home');

Route::get('/user/:id1/:id2/:id3', 'HomeController@getUser')->name('user');

Route::url('user', ['id' => 3]);

Route::prefix('/admin?')->group(function () {
    Route::get('/', 'HomeController@index')->name('admin');
    Route::get('/settings', 'HomeController@index')->name('admin.settings');
});

Route::get('/@:username', function ($username) {
    return 'kullanıcı adı: ' . $username;
})->where('username', '[a-z]+');

Route::get('/search/:search', function ($search){
    return 'Aranan kelime: ' . rawurldecode($search);
})->where('search', '.*');


Route::get('/about', function () {
    return $this->view('about');
});

Route::post('/updateUser', function () {
    return 'This is a update page';
});

Route::redirect('/php3', '/about');
