<?php

use App\Core\Router;
use App\Controllers\Api\PostController;
use App\Controllers\Api\AuthController;

/** @var Router $router */
$router = $app->getService('router');

// API routes
$router->get('/api/posts', [PostController::class, 'index']);
$router->get('/api/posts/{id}', [PostController::class, 'show']);
$router->post('/api/posts', [PostController::class, 'store']);
$router->put('/api/posts/{id}', [PostController::class, 'update']);
$router->delete('/api/posts/{id}', [PostController::class, 'destroy']);

// API Auth routes
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->post('/api/auth/register', [AuthController::class, 'register']);