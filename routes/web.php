<?php

   /*------------------------------------------------
   | Route
   |-------------------------------------------------
   |
   | This file only handles route requests
   |
   --------------------------------------------------*/


use Asgard\system\Route\Route;


Route::method('get')->route('/', 'IndexController@index')->name('home');
Route::method('post')->route('/post/deneme', 'IndexController@post')->name('post');
