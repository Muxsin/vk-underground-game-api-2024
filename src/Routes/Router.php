<?php

declare(strict_types=1);

namespace Muhsin\Vk\Routes;

class Router
{
    /**
     * @var Route[]
     */
    private array $routes;

    /**
     * @param Route[] $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            if ($route->getPattern() === $uri && $route->getMethod() === $method) {
                call_user_func($route->getController(), $route->getRequest());

                return;
            }
        }

        http_response_code(404);

        echo json_encode(['error' => 'The requested resource was not found.']);
    }
}