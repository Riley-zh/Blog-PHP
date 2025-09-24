<?php

use App\Core\App;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Middleware\CorsMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Load helper functions
require_once __DIR__ . '/../app/Utils/helpers.php';

// Initialize the application
$app = new App();

// Register core services
$app->registerService('router', new Router());
$app->registerService('request', new Request());
$app->registerService('response', new Response());

// Configure CORS
$router = $app->getService('router');
$corsMiddleware = new CorsMiddleware(
    explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*'),
    explode(',', $_ENV['CORS_ALLOWED_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS'),
    explode(',', $_ENV['CORS_ALLOWED_HEADERS'] ?? 'Content-Type,Authorization,X-Requested-With')
);
$router->setCorsMiddleware($corsMiddleware);

return $app;