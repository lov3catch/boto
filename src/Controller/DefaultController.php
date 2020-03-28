<?php

// src/Controller/DefaultController.php

namespace App\Controller;

use App\Entity\ModeratorGroup;
use App\Entity\ModeratorGroupOwners;
use App\Repository\ModeratorGroupRepository;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
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
     * @param RedisStorage $storage
     * @return Response
     */
    public function storageInfoClear(RedisStorage $storage): Response
    {
        $storage->client()->flushall();
        return new JsonResponse();
    }

    /**
     * @Route("/redis-storage", name="redis-storage")
     * @param RedisStorage $storage
     * @return Response
     */
    public function storageInfo(RedisStorage $storage): Response
    {
        return new JsonResponse($storage->client()->keys('*'));
    }

    /**
     * @Route("/redis-storage/{key}", name="redis-storage-key")
     * @param RedisStorage $storage
     * @param string $key
     * @return Response
     */
    public function storageInfoByKey(RedisStorage $storage, string $key): Response
    {
        return new JsonResponse($storage->client()->get($key));
    }

    /**
     * @Route("/qwerty", name="qwerty")
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function qweqweqwr(EntityManagerInterface $entityManager)
    {
        $entity = $entityManager->getRepository(ModeratorGroupOwners::class)->findOneBy(['group_id' => -1001208545789]);

        $entity->setIsActive(false);

        $entityManager->persist($entity);
        $entityManager->flush();

        return new JsonResponse([]);
    }

    /**
     * @Route("/phpinfo", name="phpinfo")
     * @return void
     */
    public function phpinfo()
    {
        echo phpinfo();
    }

    /**
     * @Route("/group_ids", name="group_ids")
     * @param ModeratorGroupRepository $groupRepository
     * @return JsonResponse
     */
    public function groups(ModeratorGroupRepository $groupRepository): JsonResponse
    {
        return $this->json(array_map(function (ModeratorGroup $group) {
            return $group->getGroupId();
        }, $groupRepository->findAll()));
    }
}
