<?php

namespace App\Core;

use App\Core\Logger;
use App\Core\Cache;
use App\Core\Queue\QueueManager;

class App
{
    protected array $services = [];
    protected array $config = [];

    public function __construct()
    {
        $this->loadConfig();
        $this->initializeLogger();
        $this->initializeCache();
        $this->initializeQueue();
    }

    /**
     * Register a service in the container
     */
    public function registerService(string $name, object $service): void
    {
        $this->services[$name] = $service;
    }

    /**
     * Get a service from the container
     */
    public function getService(string $name): object
    {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service {$name} not found");
        }

        return $this->services[$name];
    }

    /**
     * Load configuration files
     */
    protected function loadConfig(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config';
        if (is_dir($configPath)) {
            foreach (glob($configPath . '/*.php') as $file) {
                $configName = basename($file, '.php');
                $this->config[$configName] = require $file;
            }
        }
    }

    /**
     * Get configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $config = $this->config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }

    /**
     * Initialize the logger
     */
    protected function initializeLogger(): void
    {
        $logPath = $this->getConfig('logging.path', dirname(__DIR__, 2) . '/storage/logs/app.log');
        $logLevel = $this->getConfig('logging.level', 'debug');
        
        $this->registerService('logger', new Logger($logPath, $logLevel));
    }

    /**
     * Initialize the cache
     */
    protected function initializeCache(): void
    {
        $cacheDir = $this->getConfig('cache.path', dirname(__DIR__, 2) . '/storage/cache');
        $defaultTtl = $this->getConfig('cache.ttl', 3600);
        
        $this->registerService('cache', new Cache($cacheDir, $defaultTtl));
    }

    /**
     * Initialize the queue
     */
    protected function initializeQueue(): void
    {
        $this->registerService('queue', new QueueManager());
    }

    /**
     * Run the application
     */
    public function run(): void
    {
        /** @var Logger $logger */
        $logger = $this->getService('logger');
        $logger->info('Application started');
        
        /** @var Router $router */
        $router = $this->getService('router');
        /** @var Request $request */
        $request = $this->getService('request');

        try {
            $response = $router->dispatch($request);
            $response->send();
            $logger->info('Request handled successfully', [
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'status' => $response->getStatusCode()
            ]);
        } catch (\Exception $e) {
            $logger->error('Application error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a generic error response
            $response = new Response('500 Internal Server Error', 500);
            $response->send();
        }
    }
}