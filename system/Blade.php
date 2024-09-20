<?php

namespace Asgard\system;

use Asgard\config\Config;
use Asgard\system\View;
use Random\RandomException;

class Blade
{
    public array $config;
    public const SUFFIX = '.blade.php';
    public string $view;
    public string $viewName;
    public string $viewPath;
    public array $data;
    public array $sections = [];

    public function __construct()
    {
        $config = [
            'views' => config('blade.views'),
            'cache' => config('blade.cache'),
            'suffix' => config('blade.suffix'),
        ];
        $this->config = $config;
    }

    /**
     * @param string $view
     * @param array $data
     * @param bool $extends
     * @return false|string
     * Kullanım şekli aşağıdaki gibi:
     * $this->view(string 'viewName', array $data)
     */
    public function view(string $view, array $data = [], bool $extends = false): false|string
    {
        extract($data);

        if(!$extends){
            $this->viewName = $view;
            $this->viewPath = $this->config['views'] . '/' . $this->setViewName($view);
            $this->data = $data;
        }

        $viewPath = $this->config['views'] . '/' . $this->setViewName($view);

        if(!file_exists($viewPath)){
            throw new \Error("View '$viewPath' not found");
        }
        $this->view = file_get_contents($viewPath);
        $this->parse();

        $cachePath = $this->config['cache'] . '/' . md5($this->viewName) . '.cache.php';
        if(!file_exists($cachePath)){
            file_put_contents($cachePath, $this->view);
        }

        if(filemtime($cachePath) < filemtime($viewPath) || filemtime($cachePath) < filemtime($this->viewPath)){
            file_put_contents($cachePath, $this->view);
        }

        ob_start();
        require $cachePath;
        return ob_get_clean();
    }

    private function setViewName(string $view): string
    {
        return str_replace('.', '/', $view) . ($this->config['suffix'] ?? self::SUFFIX);
    }
    private function setViewPath(string $view): string
    {
        return $this->config['views'] . '/' . str_replace('.', '/', $view) . ($this->config['suffix'] ?? self::SUFFIX);
    }
    public function parse(): void
    {
        $this->include();
        $this->variables();
        $this->foreach();
        $this->sections();
        $this->extends();
        $this->yields();
        $this->csrf();
    }

    private function variables(): void
    {
        $this->view = preg_replace_callback('/{{(.*?)}}/', function ($matches) {
            return '<?=' . trim($matches[1]) . '?>';
        }, $this->view);
    }

    /**
     * @return void
     * Kullanım şekli aşağıdaki gibi:
     * @foreach(array)
     * -----
     * @endforeach
     */
    private function foreach(): void
    {
        $this->view = preg_replace_callback('/@foreach\((.*?)\)/', function ($matches) {
            return '<?php foreach(' . $matches[1] . '): ?>';
        }, $this->view);

        $this->view = preg_replace('/@endforeach/', '<?php endforeach; ?>', $this->view);
    }

    /**
     * @return void
     * Kullanım şekli aşağıdaki gibi:
     * @extends(\'layouts\')
     */
    private function extends(): void
    {
        $this->view = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($matches) {
            return $this->view($matches[1], $this->data, true);
        }, $this->view);
    }

    /**
     * @return void
     * Kullanım şekli aşağıdaki gibi:
     * @section('title', 'Başlık')
     *
     * @section(\'content\')
     *
     * --
     * @endsection
     */
    private function sections(): void
    {
        $this->view = preg_replace_callback('/@section\(\'(.*?)\', \'(.*?)\'\)/', function ($matches) {
            $this->sections[$matches[1]] = $matches[2];
            return '';
        }, $this->view);

        $this->view = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($matches) {
            $this->sections[$matches[1]] = $matches[2];
            return '';
        }, $this->view);
    }

    /**
     * @return void
     * Kullanım şekli aşağıdaki gibi:
     * @yield(\'content\')
     */
    private function yields(): void
    {
        $this->view = preg_replace_callback('/@yield\(\'(.*?)\'\)/', function ($matches) {
            return $this->sections[$matches[1]] ?? '';
        }, $this->view);
    }

    private function include(): void
    {
        $this->view = preg_replace_callback('/@include\(\'(.*?)\'\)/', function ($matches) {
            return file_get_contents($this->setViewPath($matches[1]));
        }, $this->view);
    }

    /**
     * @throws RandomException
     */
    private function csrf(): void
    {
        $this->view = preg_replace('/@csrf/', '<input type="hidden" name="_csrf_token" value="' . CSRFToken::generate() . '">', $this->view);
    }

}