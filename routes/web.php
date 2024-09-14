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

Route::get('/about', 'HomeController@about')->name('about');

Route::redirect('/php3', '/aboutd');
