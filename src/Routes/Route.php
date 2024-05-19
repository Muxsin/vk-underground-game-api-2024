<?php

declare(strict_types=1);

namespace Muhsin\Vk\Routes;

use Muhsin\Vk\Core\Request;

class Route
{
    private string $pattern;
    private string $method;
    /**
     * @var callable
     */
    private $controller;
    private Request $request;

    public function __construct(string $pattern, string $method, callable $controller, Request $request)
    {
        $this->pattern = $pattern;
        $this->method = $method;
        $this->controller = $controller;
        $this->request = $request;
    }

    public static function get(string $pattern, callable $controller, Request $request): self
    {
        return new self($pattern, 'GET', $controller, $request);
    }

    public static function post(string $pattern, callable $controller, Request $request): self
    {
        return new self($pattern, 'POST', $controller, $request);
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getController(): callable
    {
        return $this->controller;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}