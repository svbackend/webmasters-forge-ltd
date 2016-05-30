<?php

namespace system\components;

use Bramus\Router\Router;
use system\models\User;

/**
 * Main system class
 * Class App
 * @package system/components
 */
class App
{

    /**
     * System configuration
     * @var $config
     */
    public static $config;

    /**
     * Active connection
     * @var \PDO
     */
    public static $pdo;

    /**
     * Route list
     * @var array
     */
    public static $routes;

    /**
     * Path to "/system" folder
     * @var string
     */
    public static $systemPath;

    /**
     * Current user language
     * @var $userLanguage string
     */
    public static $userLanguage;

    /**
     * Current authenticated user
     * @var User
     */
    public static $user;

    /**
     * Default application language
     * @var $appLanguage string
     */
    public static $appLanguage;

    private static $translatedMessages;

    public function __construct(array $config)
    {
        session_start();

        self::$config = $config;
        self::$routes = $config['routes'];

        self::$appLanguage = $config['defaultLanguage'];
        self::$userLanguage = $this->getUserLanguage();

        self::$systemPath = realpath(dirname(__DIR__));
        self::$pdo = $this->getConnection($config['db']);

        self::$translatedMessages = (new Translation())->getMessages(self::$userLanguage);

        // QUERY DEBUG
        self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
    }

    public function getConnection(array $db)
    {
        $dsn = $db['driver'] . ":host={$db['host']};dbname={$db['dbname']}";

        try {
            return new \PDO($dsn, $db['username'], $db['password']);
        } catch (\PDOException $e) {
            die('Connection error: ' . $e->getMessage());
        }
    }

    public function run()
    {
        $this->requestHandler();
    }

    public function requestHandler()
    {
        $router = new Router();
        foreach (self::$routes as $pattern => $handler) {

            if (self::$config['enablePrettyUrl'] === false) {
                $pattern = '/index.php' . $pattern;
            }

            $router->match('GET|POST', $pattern, $handler);
        }

        $router->run();
    }

    /**
     * @param $category string
     * @param $message string
     * @return string
     */
    public static function t($category, $message)
    {
        if (isset(self::$translatedMessages[$category])) {

            if (isset(self::$translatedMessages[$category][$message])) {
                return self::$translatedMessages[$category][$message];
            }

        }

        return $message;
    }

    /**
     * @return string
     */
    public function getUserLanguage()
    {
        $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : self::$config['defaultLanguage'];

        if (in_array($lang, self::$config['languages'])) {
            return $lang;
        } else {
            return self::$appLanguage;
        }
    }

    /**
     * @return string
     */
    public static function home()
    {
        return 'http://' . $_SERVER['HTTP_HOST'];
    }

}