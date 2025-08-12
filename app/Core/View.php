<?php
namespace App\Core;

class View
{
    public function render(string $view, array $data = [], string $layout = 'user'): void
    {
        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View {$view} not found");
        }
        extract($data, EXTR_SKIP);
        $content = function () use ($viewFile, $data) {
            extract($data, EXTR_SKIP);
            include $viewFile;
        };
        $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            $content();
        }
    }

    public static function e(?string $str): string
    {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}