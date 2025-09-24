<?php

namespace App\Core;

class Cache
{
    protected string $cacheDir;
    protected int $defaultTtl;

    public function __construct(?string $cacheDir = null, ?int $defaultTtl = null)
    {
        $this->cacheDir = $cacheDir ?? dirname(__DIR__, 2) . '/storage/cache';
        $this->defaultTtl = $defaultTtl ?? 3600;
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Store data in cache
     */
    public function put(string $key, $data, ?int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $expiresAt = time() + $ttl;
        
        $cacheData = [
            'data' => $data,
            'expires_at' => $expiresAt
        ];
        
        $cacheFile = $this->getCacheFile($key);
        return file_put_contents($cacheFile, serialize($cacheData)) !== false;
    }

    /**
     * Get data from cache
     */
    public function get(string $key, $default = null)
    {
        $cacheFile = $this->getCacheFile($key);
        
        if (!file_exists($cacheFile)) {
            return $default;
        }
        
        $cacheData = unserialize(file_get_contents($cacheFile));
        
        // Check if cache has expired
        if ($cacheData['expires_at'] < time()) {
            unlink($cacheFile);
            return $default;
        }
        
        return $cacheData['data'];
    }

    /**
     * Check if cache key exists and is valid
     */
    public function has(string $key): bool
    {
        $cacheFile = $this->getCacheFile($key);
        
        if (!file_exists($cacheFile)) {
            return false;
        }
        
        $cacheData = unserialize(file_get_contents($cacheFile));
        return $cacheData['expires_at'] >= time();
    }

    /**
     * Remove cache entry
     */
    public function forget(string $key): bool
    {
        $cacheFile = $this->getCacheFile($key);
        
        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }
        
        return false;
    }

    /**
     * Clear all cache
     */
    public function clear(): bool
    {
        $files = glob($this->cacheDir . '/*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }

    /**
     * Get cache file path
     */
    protected function getCacheFile(string $key): string
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }
}