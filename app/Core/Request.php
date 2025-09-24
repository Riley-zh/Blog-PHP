<?php

namespace App\Core;

class Request
{
    protected string $method;
    protected string $uri;
    protected array $headers;
    protected array $get;
    protected array $post;
    protected string $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
        $this->headers = $this->getAllHeaders();
        $this->get = $_GET;
        $this->post = $_POST;
        $this->body = file_get_contents('php://input');
    }

    /**
     * Get all headers
     * Fallback for CLI environments
     */
    protected function getAllHeaders(): array
    {
        // Check if getallheaders function exists (Apache)
        if (function_exists('getallheaders')) {
            return getallheaders() ?: [];
        }
        
        // Fallback for other servers (like Nginx) and CLI
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $header = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        
        return $headers;
    }

    /**
     * Get the request method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the request URI
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Parse the URI to remove query string and base path
     */
    protected function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        
        // Remove base path if exists
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        return $uri ?: '/';
    }

    /**
     * Get a GET parameter
     */
    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Get a POST parameter
     */
    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Get a header value
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get the request body
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Get JSON data from request body
     */
    public function json(): array
    {
        return json_decode($this->body, true) ?: [];
    }
}