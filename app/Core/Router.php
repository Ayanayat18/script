<?php
namespace App\Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = $this->normalize($path);

        $handler = $this->routes[$method][$path] ?? null;
        if ($handler === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        if (is_callable($handler)) {
            call_user_func($handler);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$controller, $action] = explode('@', $handler, 2);
            $controllerClass = 'App\\Controllers\\' . $controller;
            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller {$controllerClass} not found");
            }
            $instance = new $controllerClass();
            if (!method_exists($instance, $action)) {
                throw new \RuntimeException("Action {$action} not found in {$controllerClass}");
            }
            $instance->$action();
            return;
        }

        throw new \RuntimeException('Invalid route handler');
    }

    private function normalize(string $path): string
    {
        if ($path === '') {
            return '/';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        return rtrim($path, '/') ?: '/';
    }
}