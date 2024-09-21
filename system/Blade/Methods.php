<?php

namespace Asgard\system\Blade;

use Asgard\system\CSRFToken;
use Asgard\system\Exceptions\Method\PageNotFoundException;
use Random\RandomException;

class Methods
{
    public static function variables(): void
    {
        Blade::$view = preg_replace_callback('/{{(.*?)}}/', function ($matches) {
            return '<?=' . trim($matches[1]) . '?>';
        }, Blade::$view);
    }

    /**
     * @return void
     * @throws PageNotFoundException
     * @throws RandomException
     *  The method of use is as follows:
     * @extends(\'layouts\')
     */
    public static function extends(): void
    {
        Blade::$view = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($matches) {
            return Blade::view($matches[1], Blade::$data, true);
        }, Blade::$view);
    }

    /**
     * @return void
     * The method of use is as follows:
     * @foreach(array)
     * -----
     * @endforeach
     */
    public static function foreach(): void
    {
        Blade::$view = preg_replace_callback('/@foreach\((.*?)\)/', function ($matches) {
            return '<?php foreach(' . $matches[1] . '): ?>';
        }, Blade::$view);

        Blade::$view = preg_replace('/@endforeach/', '<?php endforeach; ?>', Blade::$view);
    }

    /**
     * @return void
     * The method of use is as follows:
     * @section('title', 'Başlık')
     *
     * @section(\'content\')
     *
     * --
     * @endsection
     */
    public static function sections(): void
    {
        Blade::$view = preg_replace_callback('/@section\(\'(.*?)\', \'(.*?)\'\)/', function ($matches) {
            Blade::$sections[$matches[1]] = $matches[2];
            return '';
        }, Blade::$view);

        Blade::$view = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($matches) {
            Blade::$sections[$matches[1]] = $matches[2];
            return '';
        }, Blade::$view);
    }

    /**
     * @return void
     * The method of use is as follows:
     * @yield(\'content\')
     */
    public static function yields(): void
    {
        Blade::$view = preg_replace_callback('/@yield\(\'(.*?)\'\)/', function ($matches) {
            return Blade::$sections[$matches[1]] ?? '';
        }, Blade::$view);
    }

    public static function include(): void
    {
        Blade::$view = preg_replace_callback('/@include\(\'(.*?)\'\)/', function ($matches) {
            return file_get_contents(Query::setViewPath($matches[1]));
        }, Blade::$view);
    }

    /**
     * @throws RandomException
     */
    public static function csrf(): void
    {
        Blade::$view = preg_replace('/@csrf/', '<input type="hidden" name="_csrf_token" value="' . CSRFToken::generate() . '">', Blade::$view);
    }
}