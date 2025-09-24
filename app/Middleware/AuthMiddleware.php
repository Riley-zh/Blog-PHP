<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request): ?Response
    {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            // Redirect to login page
            return Response::redirect('/login');
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
}