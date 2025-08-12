<?php
namespace App\Core;

class App
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run(): void
    {
        try {
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
        } catch (\Throwable $e) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                http_response_code(500);
                echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
            } else {
                http_response_code(500);
                echo 'An unexpected error occurred.';
            }
        }
    }
}