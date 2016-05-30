<?php

namespace system\components;

class Request
{
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function getValue($name, $default = null, $method = 'post')
    {
        $method = strtolower($method);

        if ($method === 'post') {
            return isset($_POST[$name]) ? $_POST[$name] : $default;
        }

        if ($method === 'get') {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        }

        return isset($_FILES[$name]) ? $_FILES[$name] : $default;
    }
}