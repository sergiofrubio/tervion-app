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
                // if ($route['isApi']) {
                //     $controller->checkAuthApi();
                // } else {
                    $controller->checkAuth();
                }
                
                // Role check
                if (!empty($route['roles'])) {
                    // if (session_status() === PHP_SESSION_NONE) {
                    //     session_start();
                    // }
                    $userRole = $_SESSION['rol'] ?? '';
                    if (!in_array($userRole, $route['roles'])) {
                        // if ($route['isApi']) {
                        //     header('Content-Type: application/json');
                        //     http_response_code(403);
                        //     echo json_encode(['error' => 'No tienes permisos para acceder a este recurso.']);
                        //     exit();
                        // } else {
                            // Redirect to home or error page
                            header('Location: ' . PROJECT_ROOT . '/inicio?error=unauthorized');
                            exit();
                        // }
                    // }
                }
            }

            return $controller->$methodName();
        }

        echo "Page not found.";
    }
}
