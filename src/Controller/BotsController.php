<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BotContainer;
use App\Events\ActivityEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController
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
}
