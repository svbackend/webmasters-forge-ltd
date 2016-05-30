<?php

namespace system\components;

use League\Plates\Engine;

class Controller
{
    /**
     * Template Engine instance
     * @var Engine $view
     */
    public $view;

    /**
     * Templates root
     * @var string
     */
    public $viewsDir;

    public function __construct()
    {
        $this->view = new Engine($this->getLayoutPath());
    }


    public function getLayoutPath()
    {
        return App::$systemPath . '/views';
    }

    public function getViewPath()
    {
        // = IndexController
        $controllerClass = explode('\\', get_class($this))[2];
        // = index
        return strtolower(substr($controllerClass, 0, strlen($controllerClass) - 10));
    }

    /**
     * @param string $file
     * @param array $data
     * @return string
     */
    public function render($file = '', array $data = [])
    {
        $content = $this->view->render($this->getViewPath() . '/' . $file, $data);

        echo $content;
        return $content;
    }

    public function json($data)
    {
        header('Content-type: application/json');
        $response = json_encode($data);

        echo $response;
        return $response;
    }

    public function notFound()
    {
        echo '404';
        return '404';
    }

}