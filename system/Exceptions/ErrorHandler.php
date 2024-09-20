<?php

namespace Asgard\system\Exceptions;

use Asgard\config\Config;

class ErrorHandler
{

    public static function getStatusCode(string $key): int|string
    {
        return config('errors.' . $key) ?? 500;
    }

    /**
     * Handle errors
     *
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     */
    public static function handle(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $error = [
            'type' => self::getErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'trace' => debug_backtrace(),
            'code' => self::getStatusCode(self::getErrorType($errno))
        ];

        self::logError($error);
        self::logErrorToSession($error['message'], $error['code']);

        if (config('app.debug')) {
            self::displayError($error);
        } else {
            echo "Bir hata oluştu. Daha sonra tekrar deneyiniz.";
        }
    }

    /**
     * Get error type
     *
     * @param int $errno
     * @return string
     */
    private static function getErrorType(int $errno): string
    {
        switch ($errno) {
            case E_ERROR: return 'ERROR';
            case E_WARNING: return 'WARNING';
            case E_PARSE: return 'PARSE ERROR';
            case E_NOTICE: return 'NOTICE';
            case E_DEPRECATED: return 'DEPRECATED';
            case E_USER_DEPRECATED: return 'USER DEPRECATED';
            default: return 'UNKNOWN';
        }
    }

    /**
     * Log the error
     *
     * @param array $error
     * @return void
     */
    private static function logError(array $error): void
    {
        $errorJson = json_encode($error);

        error_log($errorJson);

        $logDirectory = dirname(__DIR__, 2) . '/storage/logs';

        if (!$logDirectory || !is_dir($logDirectory) || !is_writable($logDirectory)) {
            error_log("Log dizini mevcut değil veya yazılamıyor : " . $logDirectory);
            return;
        }

        $logFile = $logDirectory . '/error_log_' . date('Y-m-d') . '.log';

        if (file_put_contents($logFile, $errorJson . PHP_EOL, FILE_APPEND) === false) {
            error_log("Log dosyasına yazılamıyor: " . $logFile);
        }
    }
    private static function logErrorToSession(string $message, int|string $statusCode): void
    {
        $_SESSION['error'] = [
            'message' => $message,
            'status_code' => $statusCode,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    /**
     * Display the error
     *
     * @param array $error
     * @return void
     */
    private static function displayError(array $error): void
    {
        echo "<pre>";
        print_r($error);
        echo "</pre>";
    }
}

set_error_handler(['Asgard\system\Exceptions\ErrorHandler', 'handle']);
