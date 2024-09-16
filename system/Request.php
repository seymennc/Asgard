<?php

namespace Asgard\system;


class Request
{

    public function __get($name)
    {
        return $this->input($name);
    }

    public static function all()
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
    public static function input($key = null, $default = null)
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
    public static function post($key = null, $default = null)
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
    public static function get($key = null, $default = null)
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
    public static function file($key = null)
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
    public static function header($key = null)
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
    public static function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the current request URL.
     *
     * @return string
     */
    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Check if the request has a specific key.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key): bool
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
    public static function validate(array $rules)
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
            throw new \Exception(json_encode($errors));
        }
    }
}
