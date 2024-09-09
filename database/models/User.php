<?php

namespace Asgard\database\models;

use Asgard\system\Model;

class User extends Model
{
    protected $tableName = 'users'; //bu alan kullanıcı tarafından eklenmezse tablo adı belirtilmedi adında hata kodu dönecek

    public static function getData()
    {
        return User::where('status', 1)->first();
    }
}