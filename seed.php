<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Database;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get database instance
$config = require __DIR__ . '/config/database.php';
$config['driver'] = $_ENV['DB_DRIVER'] ?? $config['driver'];
$config['host'] = $_ENV['DB_HOST'] ?? $config['host'] ?? 'localhost';
$config['port'] = $_ENV['DB_PORT'] ?? $config['port'] ?? 3306;
$config['database'] = $_ENV['DB_DATABASE'] ?? $config['database'];
$config['username'] = $_ENV['DB_USERNAME'] ?? $config['username'] ?? null;
$config['password'] = $_ENV['DB_PASSWORD'] ?? $config['password'] ?? null;

$db = Database::getInstance($config);

echo "Creating test data...\n";

// Create a test user
$userModel = new User();
$userId = $userModel->createUser([
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => 'password'
]);

echo "Created user with ID: {$userId}\n";

// Create categories
$categoryModel = new Category();
$techCategoryId = $categoryModel->create([
    'name' => 'Technology',
    'slug' => 'technology',
    'description' => 'Posts about technology'
]);

$lifeCategoryId = $categoryModel->create([
    'name' => 'Lifestyle',
    'slug' => 'lifestyle',
    'description' => 'Posts about lifestyle'
]);

echo "Created categories\n";

// Create tags
$tagModel = new Tag();
$phpTagId = $tagModel->create([
    'name' => 'PHP',
    'slug' => 'php'
]);

$javascriptTagId = $tagModel->create([
    'name' => 'JavaScript',
    'slug' => 'javascript'
]);

echo "Created tags\n";

// Create posts
$postModel = new Post();
$postId1 = $postModel->create([
    'user_id' => $userId,
    'title' => 'Getting Started with Modern PHP',
    'slug' => 'getting-started-with-modern-php',
    'content' => 'This is a comprehensive guide to getting started with modern PHP development. We\'ll cover the latest features, best practices, and tools that will help you build better applications.',
    'excerpt' => 'Learn how to get started with modern PHP development',
    'status' => 'published',
    'published_at' => date('Y-m-d H:i:s')
]);

$postId2 = $postModel->create([
    'user_id' => $userId,
    'title' => 'Building a Blog with PHP and SQLite',
    'slug' => 'building-blog-with-php-sqlite',
    'content' => 'In this tutorial, we\'ll walk through building a complete blog application using PHP and SQLite. You\'ll learn about routing, database design, and creating a clean user interface.',
    'excerpt' => 'Build a complete blog application with PHP and SQLite',
    'status' => 'published',
    'published_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
]);

echo "Created posts\n";

echo "Test data created successfully!\n";