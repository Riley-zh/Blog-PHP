<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class ApiAuthMiddleware extends Middleware
{
    public function handle(Request $request): ?Response
    {
        // Get the Authorization header
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader) {
            return Response::json([
                'success' => false,
                'message' => 'Authorization header missing'
            ], 401);
        }
        
        // Check if it's a Bearer token
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid authorization header format'
            ], 401);
        }
        
        $token = $matches[1];
        
        // In a real application, you would validate the token against a database or JWT library
        // For now, we'll just check if it's not empty
        if (empty($token)) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        
        // Add user info to request or session for later use
        // This is a simplified example
        return null;
    }
}