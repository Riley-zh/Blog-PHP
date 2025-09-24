<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class RateLimitMiddleware extends Middleware
{
    protected int $maxAttempts;
    protected int $decayMinutes;
    protected string $keyPrefix;

    public function __construct(int $maxAttempts = 60, int $decayMinutes = 1, string $keyPrefix = 'rate_limit:')
    {
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
        $this->keyPrefix = $keyPrefix;
    }

    public function handle(Request $request): ?Response
    {
        $key = $this->keyPrefix . $this->getClientIp();
        $attempts = $this->getAttempts($key);
        
        if ($attempts >= $this->maxAttempts) {
            return new Response('Too Many Requests', 429);
        }
        
        $this->incrementAttempts($key);
        return null;
    }

    /**
     * Get client IP address
     */
    protected function getClientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Get number of attempts for key
     */
    protected function getAttempts(string $key): int
    {
        // In a real application, you would use Redis or a database
        // For simplicity, we'll use a file-based approach
        $file = sys_get_temp_dir() . '/' . md5($key) . '.txt';
        
        if (!file_exists($file)) {
            return 0;
        }
        
        $data = json_decode(file_get_contents($file), true);
        $now = time();
        
        // Check if the data is still valid
        if ($data['expires_at'] < $now) {
            unlink($file);
            return 0;
        }
        
        return $data['attempts'];
    }

    /**
     * Increment attempts for key
     */
    protected function incrementAttempts(string $key): void
    {
        $file = sys_get_temp_dir() . '/' . md5($key) . '.txt';
        $now = time();
        $expiresAt = $now + ($this->decayMinutes * 60);
        
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            $attempts = $data['attempts'] + 1;
        } else {
            $attempts = 1;
        }
        
        file_put_contents($file, json_encode([
            'attempts' => $attempts,
            'expires_at' => $expiresAt
        ]));
    }
}