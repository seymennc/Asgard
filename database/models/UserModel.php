<?php

namespace Asgard\database\models;

use Asgard\system\Model;

class UserModel extends Model
{
    protected $tableName = 'users'; //bu alan kullanıcı tarafından eklenmezse tablo adı belirtilmedi adında hata kodu dönecek

    public static function getData(): false|array
    {
        return UserModel::where('status', 1)->get();
    }
}