<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BashImBot\BashImBot;
use App\Botonarioum\Bots\BotContainer;
use App\Botonarioum\Bots\BotonarioumBot\BotonarioumBot;
use App\Botonarioum\Bots\SandboxBot\SandboxBot;
use App\Events\ActivityEvent;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController
{
    /**
     * @Route("/bots", name="handler")
     */
    public function index(Request $request): Response
    {
        return new Response('It works! ☺');
    }

    /**
     * @Route("/bots/{token}", name="handler_example")
     * @param Request $request
     * @param $token
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @return Response
     */
    public function handler(Request $request, $token, EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher): Response
    {
        if ($request->isMethod('post')) {
            $container = (new BotContainer())
                ->add(new BashImBot())
                ->add(new BotonarioumBot($entityManager))
                ->add(new SandboxBot());

            $bot = $container->handle($request);

            if ($bot instanceof Bot) {
                $dispatcher->dispatch(ActivityEvent::EVENT_NAME, new ActivityEvent($request, $bot));
            }
        }

        return new Response('It works!! ☺');
    }
}
