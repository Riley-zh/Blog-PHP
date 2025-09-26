<?php

namespace App\Core;

use App\Core\Contracts\CacheInterface;

class RedisCache implements CacheInterface
{
    protected \Redis $client;
    protected int $defaultTtl;

    public function __construct(array $config = [], ?int $defaultTtl = null)
    {
        if (!class_exists('\Redis')) {
            throw new \RuntimeException('Redis extension is not installed');
        }

        $this->defaultTtl = $defaultTtl ?? 3600;
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 6379;
        $timeout = $config['timeout'] ?? 1.5;

        $this->client = new \Redis();
        $this->client->connect($host, $port, $timeout);

        if (isset($config['password']) && $config['password'] !== '') {
            $this->client->auth($config['password']);
        }
    }

    public function put(string $key, $data, ?int $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $value = serialize($data);
        return $this->client->setex($key, $ttl, $value);
    }

    public function get(string $key, $default = null)
    {
        $value = $this->client->get($key);
        if ($value === false) {
            return $default;
        }
        return unserialize($value);
    }

    public function has(string $key): bool
    {
        $res = $this->client->exists($key);
        return is_int($res) ? ($res > 0) : (bool) $res;
    }

    public function forget(string $key): bool
    {
        $res = $this->client->del($key);
        return is_int($res) ? ($res > 0) : (bool) $res;
    }

    public function clear(): bool
    {
        return $this->client->flushDB();
    }
}
