<?php

namespace Config;

class RedisConfig
{

    public array $windows = [
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'persistent' => true,
    ];
    public array $linux = [
        'scheme' => 'unix',
        'path'   => '/home/fakhraho/.redis/redis.sock',
        'host'   => '127.0.0.1',
        'port'   => 6379,
        'persistent' => true,
    ];
}
