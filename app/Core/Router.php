<?php

namespace App\Core;

use App\Core\Request;
use App\Core\Response;
use App\Middleware\CorsMiddleware;

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'OPTIONS' => [],
    ];

    protected array $middleware = [];
    protected ?CorsMiddleware $corsMiddleware = null;

    /**
     * Set CORS middleware
     */
    public function setCorsMiddleware(CorsMiddleware $corsMiddleware): void
    {
        $this->corsMiddleware = $corsMiddleware;
    }

    /**
     * Register a GET route
     */
    public function get(string $uri, $callback, array $middleware = []): void
    {
        $this->routes['GET'][$this->normalizeUri($uri)] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, $callback, array $middleware = []): void
    {
        $this->routes['POST'][$this->normalizeUri($uri)] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register a PUT route
     */
    public function put(string $uri, $callback, array $middleware = []): void
    {
        $this->routes['PUT'][$this->normalizeUri($uri)] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register a DELETE route
     */
    public function delete(string $uri, $callback, array $middleware = []): void
    {
        $this->routes['DELETE'][$this->normalizeUri($uri)] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Register an OPTIONS route
     */
    public function options(string $uri, $callback, array $middleware = []): void
    {
        $this->routes['OPTIONS'][$this->normalizeUri($uri)] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    /**
     * Normalize URI by removing trailing slash
     */
    protected function normalizeUri(string $uri): string
    {
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Dispatch the request to the appropriate route
     */
    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $this->normalizeUri($request->getUri());
        
        // Check if route exists
        if (!isset($this->routes[$method][$uri])) {
            // Try to find route with parameters
            $route = $this->findRouteWithParameters($method, $uri);
            if ($route === null) {
                $response = new Response('404 Not Found', 404);
                $this->applyCorsHeaders($response, $request);
                return $response;
            }
            
            $callback = $route['callback'];
            $params = $route['params'];
            $middleware = $route['middleware'] ?? [];
        } else {
            $callback = $this->routes[$method][$uri]['callback'];
            $params = [];
            $middleware = $this->routes[$method][$uri]['middleware'] ?? [];
        }

        // Apply middleware
        foreach ($middleware as $mw) {
            $middlewareInstance = null;
            
            // Handle different middleware formats
            if (is_string($mw)) {
                // Middleware class name
                if (class_exists($mw)) {
                    $middlewareInstance = new $mw();
                }
            } elseif (is_callable($mw)) {
                // Callable middleware
                $result = call_user_func($mw, $request);
                if ($result instanceof Response) {
                    $this->applyCorsHeaders($result, $request);
                    return $result;
                }
                continue;
            } elseif (is_object($mw)) {
                // Middleware object
                $middlewareInstance = $mw;
            }
            
            // Execute middleware if it's valid
            if ($middlewareInstance && method_exists($middlewareInstance, 'handle')) {
                $result = $middlewareInstance->handle($request);
                if ($result instanceof Response) {
                    $this->applyCorsHeaders($result, $request);
                    return $result;
                }
            }
        }

        // Execute the route callback
        try {
            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$request, ...$params]);
            } elseif (is_array($callback) && count($callback) === 2) {
                [$controller, $method] = $callback;
                if (is_string($controller)) {
                    $controller = new $controller();
                }
                // Set the request object for controllers that extend Controller
                if ($controller instanceof Controller) {
                    $controller->setRequest($request);
                }
                $result = call_user_func_array([$controller, $method], [$request, ...$params]);
            } else {
                throw new \Exception('Invalid route callback');
            }

            if ($result instanceof Response) {
                $this->applyCorsHeaders($result, $request);
                return $result;
            }

            $response = new Response((string) $result);
            $this->applyCorsHeaders($response, $request);
            return $response;
        } catch (\Exception $e) {
            $response = new Response('500 Internal Server Error: ' . $e->getMessage(), 500);
            $this->applyCorsHeaders($response, $request);
            return $response;
        }
    }

    /**
     * Apply CORS headers to response
     */
    protected function applyCorsHeaders(Response $response, Request $request): void
    {
        if ($this->corsMiddleware) {
            $origin = $request->header('Origin');
            $this->corsMiddleware->setCorsHeaders($response, $origin);
        }
    }

    /**
     * Find route with parameters
     */
    protected function findRouteWithParameters(string $method, string $uri)
    {
        foreach ($this->routes[$method] as $routeUri => $routeData) {
            $pattern = preg_quote($routeUri, '/');
            $pattern = preg_replace('/\\\{([^\/]+)\\\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $paramNames = [];
                preg_match_all('/\{([^\/]+)\}/', $routeUri, $paramNames);
                $paramNames = $paramNames[1];

                $params = [];
                foreach ($matches as $index => $value) {
                    $params[$paramNames[$index] ?? $index] = $value;
                }

                return [
                    'callback' => $routeData['callback'],
                    'params' => array_values($params),
                    'middleware' => $routeData['middleware'] ?? []
                ];
            }
        }

        return null;
    }
}