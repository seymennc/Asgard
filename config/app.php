<?php

return [

    /*------------------------------------------------
    | Base Path Directory
    |-------------------------------------------------
    |
    | This is the base path directory of the project.
    |
    --------------------------------------------------*/

    'base_path' => '\\' . env('BASE_PATH', '\\'),


    /*------------------------------------------------
    | Debug Mode
    |-------------------------------------------------
    |
    | When this mode is enabled, the system provides
    | more detailed error messages and stack
    | trace information.
    |
    --------------------------------------------------*/
    'debug' => env('DEBUG', true),
  ];