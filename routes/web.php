<?php

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Controllers\CategoryController;
use App\Controllers\TagController;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

/** @var Router $router */
$router = $app->getService('router');

// Home routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);

// Authentication routes
$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegistrationForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Post routes
$router->get('/posts', [PostController::class, 'index']);
$router->get('/posts/{slug}', [PostController::class, 'show']);
$router->get('/search', [PostController::class, 'search']);

// Category routes
$router->get('/categories', [CategoryController::class, 'index']);
$router->get('/categories/{slug}', [CategoryController::class, 'show']);

// Tag routes
$router->get('/tags', [TagController::class, 'index']);
$router->get('/tags/{slug}', [TagController::class, 'show']);

// Admin routes with middleware
$router->get('/admin', [AdminController::class, 'dashboard'], [AdminMiddleware::class]);
$router->get('/admin/posts', [AdminController::class, 'posts'], [AdminMiddleware::class]);
$router->get('/admin/posts/create', [AdminController::class, 'createPost'], [AdminMiddleware::class]);
$router->post('/admin/posts', [AdminController::class, 'storePost'], [AdminMiddleware::class]);
$router->get('/admin/posts/{id}/edit', [AdminController::class, 'editPost'], [AdminMiddleware::class]);
$router->post('/admin/posts/{id}', [AdminController::class, 'updatePost'], [AdminMiddleware::class]);
$router->get('/admin/posts/{id}/delete', [AdminController::class, 'deletePost'], [AdminMiddleware::class]);

// Profile route with auth middleware
$router->get('/profile', [AuthController::class, 'profile'], [AuthMiddleware::class]);