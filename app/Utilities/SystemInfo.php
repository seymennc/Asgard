<?php

namespace App\Utilities;

class SystemInfo
{
    /**
     * SystemInfo constructor.
     */
    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @return object
     */
    public static function environments(): object
    {
        return (object) [
            'project_name' => env('PROJECT_NAME'),
            'base_path' => env('BASE_PATH'),
            'debug' => env('DEBUG'),
            'environment' => env('ENVIRONMENT'),
            'framework_version' => env('VERSION'),
        ];
    }

    /**
     * @return object
     */
    public static function server(): object
    {
        return (object) [
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'client_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'phpVersion' => phpversion(),
            'loaded_extensions' => get_loaded_extensions(),
            'memory_usage' => round(memory_get_usage() / 1048576, 2) . 'MB / ' . ini_get('memory_limit'),
            'operating_system' => php_uname(),
            'max_execution_time' => ini_get('max_execution_time'),
            'uptime' => shell_exec('uptime') ?? 'Unknown',
            'cpu_usage' => shell_exec('top -bn1 | grep "Cpu(s)"') ?: 'N/A',
            'active_connections' => shell_exec('netstat -an | grep ESTABLISHED | wc -l') ?: 'N/A',
        ];
    }
}