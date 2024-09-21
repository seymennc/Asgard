<?php

namespace Asgard\system\Blade;

use AllowDynamicProperties;
use Asgard\system\Exceptions\Method\PageNotFoundException;
use Random\RandomException;

#[AllowDynamicProperties] class Blade
{
    public static array $config;

    public const SUFFIX = '.blade.php';
    public static string $view;
    public static string $viewName;
    public static string $viewPath;
    public static array $data;
    public static array $sections = [];

    public function __construct()
    {
        $config = [
            'views' => config('blade.views'),
            'cache' => config('blade.cache'),
            'suffix' => config('blade.suffix'),
        ];
        self::$config = $config;
    }

    /**
     * @param string $view
     * @param array $data
     * @param bool $extends
     * @return false|string
     * The method of use is as follows:
     * $this->view(string 'viewName', array $data)
     * @throws PageNotFoundException|RandomException
     */
    public static function view(string $view, array $data = [], bool $extends = false): false|string
    {
        $instance = new self();
        extract($data);

        if(!$extends){
            self::$viewName = $view;
            self::$viewPath = self::$config['views'] . '/' . Query::setViewName($view);
            self::$data = $data;
        }

        $viewPath = self::$config['views'] . '/' . Query::setViewName($view);

        if(!file_exists($viewPath)){
            throw new PageNotFoundException("View '$viewPath' not found");
        }
        self::$view = file_get_contents($viewPath);
        $instance->parse();

        Query::setCache();
        return ob_get_clean();
    }

    /**
     * @return void
     * @throws RandomException
     * @throws PageNotFoundException
     */
    public function parse(): void
    {
        Methods::include();
        Methods::variables();
        Methods::foreach();
        Methods::sections();
        Methods::extends();
        Methods::yields();
        Methods::csrf();
    }
}