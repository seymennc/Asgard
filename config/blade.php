<?php

return [

    /*------------------------------------------------
    | Suffix
    |-------------------------------------------------
    |
    | This value sets the file extension
    | in the views folder.
    |
    --------------------------------------------------*/

    'suffix' => '.blade.php',


    /*------------------------------------------------
    | Views Folder Data
    |-------------------------------------------------
    |
    | Specifies the exact location of the views folder
    | used in the blade structure
    |
    --------------------------------------------------*/

    'views' => dirname(__DIR__) . '/resources/views',


    /*------------------------------------------------
    | Cache Folder Data
    |-------------------------------------------------
    |
    | Specifies the exact location of the cache folder
    | used in the blade structure
    |
    --------------------------------------------------*/

    'cache' => dirname(__DIR__) . '/storage/cache',
];