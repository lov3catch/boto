<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BotContainer;
use App\Entity\Element;
use App\Entity\ElementType;
use App\Entity\Platform;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController extends AbstractController
{
    /**
     * @Route("/echo", name="handler")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return new Response('It works! ☺');
    }

    /**
     * @Route("/bots/{token}", name="handler_example")
     * @param Request $request
     * @param $token
     * @param BotContainer $botContainer
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     */
    public function handler(Request $request, $token, BotContainer $botContainer, EventDispatcherInterface $dispatcher): Response
    {
        if ($request->isMethod('post')) {
            $botContainer->handle($token, $request);
        }

        return new Response('It works!! ☺');
    }

    /**
     * @Route("/add/group", name="add_group")
     * @param Request $request
     * @return Response
     */
    public function addGroup(Request $request, EntityManagerInterface $entityManager): Response
    {
        // https://t.me/deezer_music_bot    - bot
        // https://t.me/disgustingmen       - group
        // ???

        $count = ($entityManager->getRepository(Element::class))->count([]);

        $entityType = $entityManager->find(ElementType::class, 1);
        $platform = $entityManager->find(Platform::class, 1);

        $group = new Element();
        $group->setId(++$count);
        $group->setTypeId($entityType);
        $group->setPlatformId($platform);
        $group->setDescription('example');
        $group->setName('example');
        $group->setStatus(false);
        $group->setUrl('qwert');

        $form = $this->createFormBuilder($group)
            ->add('name', TextType::class)
            ->getForm();

//        $entityManager->persist($group);
//
//        $entityManager->flush();

        return $this->render('add/add.html.twig', ['form' => $form->createView()]);
        return new Response('Add group');
    }

    /**
     * @Route("/add/bot", name="add_bot")
     * @param Request $request
     * @return Response
     */
    public function addBot(Request $request): Response
    {
        return new Response('Add bot');
    }

    /**
     * @Route("/add/game", name="add_game")
     * @param Request $request
     * @return Response
     */
    public function addGame(Request $request): Response
    {
        return new Response('Add game');
    }
}
