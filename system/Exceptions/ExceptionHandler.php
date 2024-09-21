<?php

namespace Asgard\system\Exceptions;

use Asgard\config\Config;

class ExceptionHandler
{
    /**
     * @param $exception
     * @return void
     */
    public static function handle($exception): void
    {

        $error = [
            'type' => 'Asgard\system\Exceptions\EXCEPTION',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
            'code' => method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500
        ];




        self::logError($error);
        self::logErrorToSession($error['message'], $error['code']);

        if (config('app.debug')) {

            require __DIR__ . '/resources/views/main.blade.php';
        } else {
            echo "Bir hata oluştu. Daha sonra tekrar deneyiniz.";
        }
    }

    /**
     * @param $error
     * @return void
     */
    private static function logError($error): void
    {
        $errorJson = json_encode($error);

        error_log($errorJson);
        $logDirectory = realpath(dirname(__DIR__) . '/../storage/logs');

        if (!$logDirectory || !is_dir($logDirectory) || !is_writable($logDirectory)) {
            error_log("Log dizini mevcut değil veya yazılamıyor: " . $logDirectory);
            return;
        }

        $logFile = $logDirectory . '/error_log_' . date('Y-m-d') . '.log';

        if (file_put_contents($logFile, $errorJson . PHP_EOL, FILE_APPEND) === false) {
            error_log("Log dosyasına yazılamıyor: " . $logFile);
        }
    }
    private static function logErrorToSession(string $message, int $statusCode): void
    {
        $_SESSION['error'] = [
            'message' => $message,
            'status_code' => $statusCode,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * @param $error
     * @return void
     */
    private static function displayError($error): void
    {
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }
}
set_exception_handler(['Asgard\system\Exceptions\ExceptionHandler', 'handle']);