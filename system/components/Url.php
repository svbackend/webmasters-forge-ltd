<?php

namespace system\components;


class Url
{
    public static function to($route = '')
    {
        if (App::$config['enablePrettyUrl'] === false) {
            $route = 'index.php/' . $route;
        }
        return App::home() . '/' . $route;
    }

    public static function asset($path)
    {
        return App::home() . '/assets/' . $path;
    }

    public static function file($path)
    {
        return App::home() . '/files/' . $path;
    }

    public static function toPath($path = '')
    {
        $root = $_SERVER['DOCUMENT_ROOT'];

        if (!empty($path)) {
            return $root . DIRECTORY_SEPARATOR . $path;
        }

        return $root;
    }

    public static function back()
    {
        return $_SERVER['HTTP_REFERER'];
    }
}