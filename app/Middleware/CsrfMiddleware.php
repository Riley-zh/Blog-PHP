<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware extends Middleware
{
    public function handle(Request $request): ?Response
    {
        // Skip CSRF check for GET requests
        if ($request->getMethod() === 'GET') {
            return null;
        }

        // Get CSRF token from request
        $token = $request->post('_token') ?: $request->header('X-CSRF-TOKEN');

        // Validate token
        if (!$this->validateToken($token)) {
            return new Response('Invalid CSRF token', 419);
        }

        return null;
    }

    /**
     * Generate a CSRF token
     */
    public static function generateToken(): string
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    protected function validateToken(?string $token): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!$token || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}