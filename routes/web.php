<?php

   /*------------------------------------------------
   | Route
   |-------------------------------------------------
   |
   | This file only handles route requests
   |
   --------------------------------------------------*/


use Asgard\System\Route;


Route::method('get')->route('/', 'HomeController@index')->name('home');
Route::method('get')->route('/about/deneme/test', 'HomeController@about')->name('about');
Route::method('post')->route('/post/deneme', 'HomeController@post')->name('post');
