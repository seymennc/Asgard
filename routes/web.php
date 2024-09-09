<?php

/*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | This file only handles route requests
    |
    |
    |
    */

use Asgard\System\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::get('/user/{id}', 'HomeController@getUser')->name('user');

Route::prefix('/admin?')->group(function () {
    Route::get('/', 'HomeController@admin')->name('admin');
    Route::get('/settings', 'HomeController@index')->name('admin.settings');
});

Route::get('/@{username}', function ($username) {
    return 'kullanıcı adı: ' . $username;
})->where('username', '[a-z]+');

Route::get('/search/:search', function ($search){
    return 'Aranan kelime: ' . rawurldecode($search);
})->where('search', '.*');


Route::get('/about', 'HomeController@about')->middleware('deneme')->name('about');

Route::post('/updateUser', function () {
    return 'This is a update page';
});

Route::redirect('/php3', '/aboutd');
