<?php

namespace App\Core\Contracts;

interface CacheInterface
{
    public function put(string $key, $data, ?int $ttl = null): bool;
    public function get(string $key, $default = null);
    public function has(string $key): bool;
    public function forget(string $key): bool;
    public function clear(): bool;
}
