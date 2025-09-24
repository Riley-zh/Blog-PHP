<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CorsMiddleware extends Middleware
{
    protected array $allowedOrigins;
    protected array $allowedMethods;
    protected array $allowedHeaders;

    public function __construct(
        array $allowedOrigins = ['*'],
        array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        array $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With']
    ) {
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
        $this->allowedHeaders = $allowedHeaders;
    }

    public function handle(Request $request): ?Response
    {
        $origin = $request->header('Origin');
        
        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response('', 200);
            $this->setCorsHeaders($response, $origin);
            return $response;
        }
        
        // For actual requests, we'll set CORS headers in the response
        // The Router will need to handle this properly
        return null;
    }

    /**
     * Set CORS headers on response
     */
    public function setCorsHeaders(Response $response, ?string $origin = null): void
    {
        // Determine allowed origin
        $allowedOrigin = $this->getAllowedOrigin($origin);
        
        if ($allowedOrigin) {
            $response->addHeader('Access-Control-Allow-Origin', $allowedOrigin);
        }
        
        $response->addHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods));
        $response->addHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders));
        $response->addHeader('Access-Control-Allow-Credentials', 'true');
        $response->addHeader('Access-Control-Max-Age', '86400'); // 24 hours
    }

    /**
     * Get allowed origin
     */
    protected function getAllowedOrigin(?string $origin): ?string
    {
        if (in_array('*', $this->allowedOrigins)) {
            return '*';
        }
        
        if ($origin && in_array($origin, $this->allowedOrigins)) {
            return $origin;
        }
        
        return null;
    }
}