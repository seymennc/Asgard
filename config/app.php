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


    /*------------------------------------------------
    | Environment
    |-------------------------------------------------
    |
    | This value determines the current environment
    | of the application. This may determine how you
    | prefer to configure various services the
    | application utilizes.
    |
    --------------------------------------------------*/

    'environment' => env('ENVIRONMENT', 'development'),


    /*------------------------------------------------
    | Timezone
    |-------------------------------------------------
    |
    | Here you may specify the default timezone for
    | your application, which will be used by the PHP
    | date and date-time functions.
    |
    --------------------------------------------------*/

    'timezone' => env('SERVER_TIMEZONE', 'Europe/Istanbul'),


    /*------------------------------------------------
    | Version
    |-------------------------------------------------
    |
    | This is the version of the application.
    |
    --------------------------------------------------*/

    'version' => env('VERSION', '1.0.0'),


    /*------------------------------------------------
    | Locale
    |-------------------------------------------------
    |
    | The locale determines the default locale that
    | will be used by the translation service provider.
    |
    --------------------------------------------------*/

    'locale' => env('LOCALE', 'en'), //Not yet used


    /*------------------------------------------------
    | Fallback Locale
    |-------------------------------------------------
    |
    | The fallback locale determines the locale that
    | will be used when the current locale is not
    | available.
    |
    --------------------------------------------------*/

    'fallback_locale' => env('FALLBACK_LOCALE', 'en'), //Not yet used


    /*------------------------------------------------
    | Encoding
    |-------------------------------------------------
    |
    | The encoding determines the default encoding
    | that will be used by the application.
    |
    --------------------------------------------------*/

    'encoding' => env('ENCODING', 'UTF-8'), //Not yet used


    /*------------------------------------------------
    | Date Format
    |-------------------------------------------------
    |
    | The date format determines the default date
    | format that will be used by the application.
    |
    --------------------------------------------------*/

    'date_format' => env('DATE_FORMAT', 'Y-m-d H:i:s'), //Not yet used


    /*------------------------------------------------
    | Database Connection
    |-------------------------------------------------
    |
    | The database connection determines the default
    | database connection that will be used by the
    | application.
    |
    --------------------------------------------------*/

    'db_connection' => env('DB_CONNECTION', 'mysql'),
    'db_host' => env('DB_HOST', '127.0.0.1'),
    'db_port' => env('DB_PORT', '3306'),
    'db_charset' => env('DB_CHARSET', 'utf8mb4'),
    'db_name' => env('DB_DATABASE', 'forge'),
    'db_username' => env('DB_USERNAME', 'forge'),
    'db_password' => env('DB_PASSWORD', ''),

  ];