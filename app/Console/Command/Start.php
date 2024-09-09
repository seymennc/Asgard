<?php

namespace Asgard\app\Console\Command;

class Start
{
    const COLOR_BLUE_BG = "\033[44m";
    const COLOR_WHITE = "\033[97m";
    const COLOR_RESET = "\033[0m";
    const COLOR_GREEN = "\033[32m";

    public function handle()
    {
        $host = '127.0.0.1';
        $port = 8000;

        $this->info('INFO');
        echo "PHP Server is " . $this->success('running') . " on [http://{$host}:{$port}]" . PHP_EOL . PHP_EOL;
        echo 'Quit the server with CONTROL-C.' . PHP_EOL;

        $output = [];
        exec("php -S {$host}:{$port} -t storage 2>&1", $output);
    }

    protected function info($message)
    {
        $boxWidth = strlen($message) - 4; // Kutu genişliği (mesaj uzunluğu + padding)
        echo PHP_EOL;
        // Üst sınır
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . str_repeat(' ', $boxWidth) . self::COLOR_RESET;

        // Mesaj içeriği
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . " " . $message . " " . self::COLOR_RESET . " ";

        // Alt sınır
        echo self::COLOR_BLUE_BG . self::COLOR_WHITE . str_repeat(' ', $boxWidth) . self::COLOR_RESET;
    }
    protected function success($message)
    {
        return self::COLOR_GREEN . $message . self::COLOR_RESET;
    }
}