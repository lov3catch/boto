<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index()
    {
        return $this->render('catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }

    /**
     * @Route("/catalogue/bots", name="catalogue_list_bots")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function bots(EntityManagerInterface $entityManager): Response
    {
        $entities = $entityManager->getRepository(Element::class)->findBy(['type' => 2]);
        return $this->render('catalogue/list.html.twig', [
            'title'           => 'Список',
            'controller_name' => 'CatalogueController',
            'entities'        => $entities,
        ]);
    }

//    /**
//     * @Route("/catalogue/bots/details/{id}", name="catalogue_details_bot")
//     * @param int $id
//     * @param EntityManagerInterface $entityManager
//     * @return Response
//     */
//    public function bot_details(int $id, EntityManagerInterface $entityManager): Response
//    {
//        return $this->render('catalogue/detail.html.twig', [
//            'controller_name' => 'CatalogueController',
//            'entities'        => [],
//            'element'         => $entityManager->getRepository(Element::class)->find($id),
//        ]);
//    }

    /**
     * @Route("/catalogue/groups", name="catalogue_list_groups")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function groups(EntityManagerInterface $entityManager): Response
    {
        $entities = $entityManager->getRepository(Element::class)->findBy(['type' => 2]);
        return $this->render('catalogue/list.html.twig', [
            'title'           => 'Список',
            'controller_name' => 'CatalogueController',
            'entities'        => $entities,
        ]);
    }

    /**
     * @Route("/catalogue/element/details/{id}", name="catalogue_element_detail")
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function details(int $id, EntityManagerInterface $entityManager): Response
    {
        return $this->render('catalogue/detail.html.twig', [
            'controller_name' => 'CatalogueController',
            'entities'        => [],
            'element'         => $entityManager->getRepository(Element::class)->find($id),
        ]);
    }

    /**
     * @Route("/catalogue/element/add", name="catalogue_element_add")
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('catalogue/add.html.twig', [
            'title' => 'Добавление',
        ]);
    }
}
