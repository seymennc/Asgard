<?php

namespace Asgard\app\Console\Command;

class Start
{
    const COLOR_BLUE_BG = "\033[44m";
    const COLOR_WHITE = "\033[97m";
    const COLOR_RESET = "\033[0m";
    const COLOR_GREEN = "\033[32m";

    public function handle(): void
    {
        $host = env('SERVER_HOST', 'localhost');
        $port = env('SERVER_PORT', 8000);

        $this->info('INFO');
        echo "PHP Server is " . $this->success('running') . " on [http://{$host}:{$port}]" . PHP_EOL . PHP_EOL;
        echo 'Quit the server with CONTROL-C.' . PHP_EOL;

        $output = [];
        exec("php -S {$host}:{$port} -t assets 2>&1", $output);
    }

    /**
     * @param $message
     * @return void
     */
    protected function info($message): void
    {
        $boxWidth = strlen($message) - 4;
        echo PHP_EOL;
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . str_repeat(' ', $boxWidth) . self::COLOR_RESET;
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . " " . $message . " " . self::COLOR_RESET . " ";
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . str_repeat(' ', $boxWidth) . self::COLOR_RESET;
    }

    /**
     * @param $message
     * @return string
     */
    protected function success($message): string
    {
        return self::COLOR_GREEN . $message . self::COLOR_RESET;
    }
}