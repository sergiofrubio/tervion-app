<?php
namespace App\Routes;

class Router
{
    protected $routes = [];

    public function add($method, $url, $action, $auth = true, $roles = [])
    {
        $this->routes[$method][$url] = [
            'action' => $action,
            'auth' => $auth,
            'roles' => $roles
        ];
    }

    public function handleRequest()
    {
        $requestUrl = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $requestUrl = strtok($requestUrl, '?');

        if (defined('PROJECT_ROOT') && PROJECT_ROOT !== '' && strpos($requestUrl, PROJECT_ROOT) === 0) {
            $requestUrl = substr($requestUrl, strlen(PROJECT_ROOT));
        }

        if ($requestUrl === '') {
            $requestUrl = '/';
        }

        if (isset($this->routes[$requestMethod][$requestUrl])) {
            $route = $this->routes[$requestMethod][$requestUrl];
            list($controllerName, $methodName) = explode('@', $route['action']);
            $controllerName = "App\\Controllers\\" . $controllerName;
            $controller = new $controllerName();

            if ($route['auth']) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (!isset($_SESSION['usuario_id'])) {
                    require_once __DIR__ . '/../Views/404.php';
                    exit();
                }
            }
                
            // Role check
            if (!empty($route['roles'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $userRole = $_SESSION['rol'] ?? '';
                if (!in_array($userRole, $route['roles'])) {
                    require_once __DIR__ . '/../Views/404.php';
                    exit();
                }
            }

            return $controller->$methodName();
        }

        require_once __DIR__ . '/../Views/404.php';
        exit();
    }
}
