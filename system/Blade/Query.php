<?php

namespace Asgard\system\Blade;

class Query
{
    /**
     * @param string $view
     * @return string
     */
    public static function setViewName(string $view): string
    {
        return str_replace('.', '/', $view) . (Blade::$config['suffix'] ?? Blade::SUFFIX);
    }

    /**
     * @param string $view
     * @return string
     */
    public static function setViewPath(string $view): string
    {
        return Blade::$config['views'] . '/' . str_replace('.', '/', $view) . (Blade::$config['suffix'] ?? Blade::SUFFIX);
    }

    public static function setCache(): void
    {
        $cachePath = Blade::$config['cache'] . '/' . md5(Blade::$viewName) . '.cache.php';
        if(!file_exists($cachePath)){
            file_put_contents($cachePath, Blade::$view);
        }

        if(filemtime($cachePath) < filemtime(Blade::$viewPath) || filemtime($cachePath) < filemtime(Blade::$viewPath)){
            file_put_contents($cachePath, Blade::$view);
        }
        ob_start();
        require $cachePath;
    }
}