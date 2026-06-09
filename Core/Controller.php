<?php
namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        require_once "../Views/$view.php";
    }

    public function model($model)
    {
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . PROJECT_ROOT . '/login');
            exit();
        }
    }
}
