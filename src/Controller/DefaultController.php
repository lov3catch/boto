<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Storages\RedisStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return new Response('It works! ☺');
    }

    /**
     * @Route("/redis-storage-clear", name="redis-storage-clear")
     */
    public function storageInfoClear(RedisStorage $storage): Response
    {
        $storage->client()->flushall();
//        var_dump($storage->client()->keys('*'));
        return new JsonResponse();
    }

    /**
 * @Route("/redis-storage", name="redis-storage")
 */
    public function storageInfo(RedisStorage $storage): Response
    {
//        var_dump($storage->client()->keys('*'));
        return new JsonResponse($storage->client()->keys('*'));
    }

    /**
     * @Route("/redis-storage/{key}", name="redis-storage-key")
     */
    public function storageInfoByKey(RedisStorage $storage, string $key): Response
    {
//        $storage->client()->set($key, 'aaaa');
//        var_dump($storage->client()->get($key));die;
        return new JsonResponse($storage->client()->get($key));
    }
}
