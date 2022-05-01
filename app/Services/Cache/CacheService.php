<?php


namespace App\Services\Cache;



use Redis;

class CacheService
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(env('REDIS_HOST', '127.0.0.1'));
        $this->redis->auth(env('REDIS_PASSWORD', ''));
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function set(string $key, string $data, int $ttl)
    {
        $this->redis->set($key, $data, $ttl);
    }

    public function delete(string $key): int
    {
        return $this->redis->del($key);
    }
}
