<?php declare(strict_types=1);

namespace App\Storages;

use Predis\Client;

class RedisStorage
{
    /**
     * @var Client
     */
    private $redisClient;

    public function __construct(string $redisUrl)
    {
        $this->redisClient = new Client($redisUrl);
    }

    public function client(): Client
    {
        return $this->redisClient;
    }
}