<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AdminMiddleware extends Middleware
{
    public function handle(Request $request): ?Response
    {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            return Response::redirect('/login');
        }

        // Check if user is admin
        if (!$this->isAdmin()) {
            return new Response('Access denied. Admin privileges required.', 403);
        }

        return null;
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin(): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // In a real application, you would check the user's role in the database
        // For now, we'll just check if the user_id is 1 (admin user)
        return isset($_SESSION['user_id']) && $_SESSION['user_id'] == 1;
    }
}