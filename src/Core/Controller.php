<?php

namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        if (str_starts_with($view, '/') || (strlen($view) > 1 && $view[1] === ':')) {
            require_once $view;
        } elseif (str_starts_with($view, '@modules/')) {
            $viewPath = dirname(__DIR__, 2) . '/modules/' . substr($view, 9) . '.php';
            require_once $viewPath;
        } else {
            require_once "../src/Views/$view.php";
        }
    }

    public function model($model)
    {
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }

    protected function exitApp()
    {
        exit();
    }
}
