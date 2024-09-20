<?php

namespace Asgard\system;


use Asgard\system\Exceptions\Method\RequestException;

class Request
{

    public string $url;
    public string $method;


    public function __construct()
    {
        $this->url = self::getUrl();
        $this->method = self::getMethod();
    }

    /**
     * Get a value from the request data (GET, POST, etc.)
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->input($name);
    }

    /**
     * Get all request data (GET, POST, etc.)
     *
     * @return array
     */
    public static function all(): array
    {
        return $_REQUEST;
    }

    /**
     * Get a value from the request data (GET, POST, etc.)
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function input(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_REQUEST;
        }
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Get a value from the POST data.
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function post(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get a value from the GET data.
     *
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Get uploaded files.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function file(string $key = null): mixed
    {
        if ($key === null) {
            return $_FILES;
        }
        return $_FILES[$key] ?? null;
    }

    /**
     * Get headers from the request.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function header(string $key = null): mixed
    {
        $headers = getallheaders();
        if ($key === null) {
            return $headers;
        }
        return $headers[$key] ?? null;
    }

    /**
     * Get the request method (GET, POST, etc.).
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the current request path.
     *
     * @return array|string
     */
    public static function postData(): array|string
    {
        $data = $_POST;
        if (empty($data)) {
            return $data = 'empty';
        }
        return $data;
    }

    /**
     * Get the current request path.
     *
     * @return array|string
     */
    public static function getData(): array|string
    {
        $data = $_GET;
        if (empty($data)) {
            return $data = 'empty';
        }
        return $data;
    }
    /**
     * Get the current request path.
     *
     * @return string
     */
    public static function getUrl(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Get the current request query parameters.
     *
     * @return array|string
     */
    public static function getQueryParams(): array|string
    {
        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        if ($query === null) {
            return $query = 'empty';
        }

        parse_str($query, $queryParams);
        return $queryParams;
    }

    /**
     * Get the current request cookies.
     *
     * @return array|string
     */
    public static function getCookies(): array|string
    {
        $cookies = [];
        foreach ($_COOKIE as $name => $value) {
            $cookies[] = [
                'name' => $name,
                'value' => $value,
            ];
        }
        return $cookies ?? 'empty';
    }
    /**
     * Get the current request files.
     *
     * @return array|string
     */
    public static function getFiles(): array|string
    {
        $data = $_FILES;
        if (empty($data)) {
            return $data = 'empty';
        }
        return $data;
    }
    /**
     * Get the current request headers.
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            // getallheaders() function may not be available on servers other than Apache
            // In this case you can manually collect headers from the $_SERVER supersphere.
            $headers = [];
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $header = str_replace('_', '-', strtolower(substr($key, 5)));
                    $headers[$header] = $value;
                }
            }
            return $headers ?? ['No headers found'];
        }
    }

    /**
     * Get the current request body.
     *
     * @return string|null
     */
    public static function getAuthorizationToken(): ?string
    {
        $instance = new self();
        $headers = $instance->getHeaders();
        return $headers['Authorization'] ?? 'empty';
    }

    /**
     * Get the current request body.
     *
     * @return string
     */
    public static function getContentType(): string
    {
        return $_SERVER['CONTENT_TYPE'] ?? 'empty';
    }

    /**
     * Get the current request body.
     *
     * @return array
     */
    public static function getJsonPayload(): array|string
    {
        $instance = new self();
        $json = [];
        if ($instance->getContentType() === 'application/json') {
            $json = file_get_contents('php://input');
            return json_decode($json, true) ?? [];
        }else{
            $json = 'empty';
        }
        return $json;
    }

    /**
     * Get the current request body.
     *
     * @return array|null
     */
    public static function getSession(): array|string
    {
        return $_SESSION ?? 'empty';
    }
    /**
     * Check if the request has a specific key.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_REQUEST[$key]);
    }

    /**
     * Validate the request data against the given rules.
     *
     * @param array $rules
     * @return void
     * @throws \Exception
     */
    public static function validate(array $rules): void
    {
        $errors = [];
        $defaultPattern = '/^[a-zA-Z0-9\s\.\-\_]+$/';

        foreach ($rules as $key => $ruleSet) {
            $value = self::input($key);
            $rules = explode('|', $ruleSet);

            $skipRegex = in_array('no-regex', $rules);

            if (!$skipRegex && $value !== null && !preg_match($defaultPattern, $value)) {
                $errors[$key][] = 'The ' . $key . ' field has an invalid format';
            }

            if ($value === null) {
                $errors[$key][] = 'The ' . $key . ' field cannot be null';
                continue;
            }

            foreach ($rules as $rule) {
                if (str_contains($rule, 'required') && empty($value)) {
                    $errors[$key][] = 'The ' . $key . ' field is required';
                }

                if (str_contains($rule, 'email') && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$key][] = 'The ' . $key . ' field must be a valid email address';
                }

                if (str_contains($rule, 'url') && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $errors[$key][] = 'The ' . $key . ' field must be a valid URL';
                }

                if (str_contains($rule, 'numeric') && !is_numeric($value)) {
                    $errors[$key][] = 'The ' . $key . ' field must be a number';
                }

                if (str_contains($rule, 'min')) {
                    $min = explode(':', $rule)[1];
                    if (strlen($value) < $min) {
                        $errors[$key][] = 'The ' . $key . ' field must be at least ' . $min . ' characters';
                    }
                }

                if (str_contains($rule, 'max')) {
                    $max = explode(':', $rule)[1];
                    if (strlen($value) > $max) {
                        $errors[$key][] = 'The ' . $key . ' field must be at most ' . $max . ' characters';
                    }
                }

                if (str_contains($rule, 'in')) {
                    $in = explode(':', $rule)[1];
                    $in = explode(',', $in);
                    if (!in_array($value, $in)) {
                        $errors[$key][] = 'The ' . $key . ' field must be one of the following: ' . implode(', ', $in);
                    }
                }

                if (str_contains($rule, 'not_in')) {
                    $not_in = explode(':', $rule)[1];
                    $not_in = explode(',', $not_in);
                    if (in_array($value, $not_in)) {
                        $errors[$key][] = 'The ' . $key . ' field must not be one of the following: ' . implode(', ', $not_in);
                    }
                }

                if (str_contains($rule, 'unique')) {
                    $params = explode(':', $rule);
                    $table = $params[1];
                    $column = $params[2];
                    $result = Database::table($table)->where($column, $value)->first();
                    if ($result) {
                        $errors[$key][] = 'The ' . $key . ' field must be unique';
                    }
                }

                if (str_contains($rule, 'regex')) {
                    $pattern = explode(':', $rule)[1];
                    if (!preg_match($pattern, $value)) {
                        $errors[$key][] = 'The ' . $key . ' field has an invalid format';
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw new RequestException(json_encode($errors));
        }
    }
}
