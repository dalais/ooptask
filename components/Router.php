<?php


namespace MyClasses\components;


class Router {

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }


    /*
     * Возвращает строку запроса
     * */
    private function getUri()
    {
        if (! empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {

        // Получение строки запроса
        $uri = $this->getUri();

        // Проверить наличие запроса в маршрутах (routes.php)
        foreach ($this->routes as $uriPattern => $path) {

            if (preg_match("~$uriPattern~", $uri)) {

                // Получаем внутренний путь из внешнего по правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определение контроллера и action обрабатывающего запрос
                $piece = explode('/', $internalRoute);

                $controllerName = array_shift($piece).'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action'.ucfirst(array_shift($piece));

                $parameters = $piece;

                // Подключение файла класса-контроллера

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {

                    include_once($controllerFile);
                }

                $controllerObject = new $controllerName;

                $res = call_user_func_array([$controllerObject, $actionName], $parameters);

                if ($res != null) {
                    break;
                } else {
                    header("Localhost: /");
                }
            }
        }


    }
}