<?php
namespace App\Core;

class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    protected function render(string $view, array $data = [], string $layout = 'user'): void
    {
        $this->view->render($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    protected function requireRole(array $roles): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        if (!in_array(Auth::user()['role'] ?? '', $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}