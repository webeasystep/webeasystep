<?php

namespace App\Libraries;
use Predis\Client;
use Config\RedisConfig; // Assuming you have a RedisConfig file in your Config directory

class Predis
{
    private static ?Predis $instance = null;
    protected Client $redis;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        // Load Redis configuration from a config file
        $config = new RedisConfig();

        try {
            $currentConfig = env('CI_ENVIRONMENT') == 'development' ? $config->windows : $config->linux ;
            $this->redis = new Client($currentConfig);
        } catch (\Exception $e) {
            // Handle connection error appropriately
            log_message('error', 'Redis connection failed: ' . $e->getMessage());
            // Optionally, re-throw the exception if you want to handle it further up the call stack
            throw $e;
        }
    }

    public static function getInstance(): ?Predis
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the Redis client instance.
     *
     * @return Client
     */
    public function getRedis(): Client
    {
        return $this->redis;
    }

}
