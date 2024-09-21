<?php

/*------------------------------------------------
| Route
|-------------------------------------------------
|
| This file only handles route requests
|
--------------------------------------------------*/


use Asgard\system\Route\Route;


Route::method('get')->route('/', function (){
    echo "ok";
})->middleware('index');
Route::method('get')->route('/deneme', function (){
    echo "ok deneme sayfası";
})->name('deneme');
Route::method('post')->route('/post/deneme', 'IndexController@post')->name('post');