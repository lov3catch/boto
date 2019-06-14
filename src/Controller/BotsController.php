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
     * @Route("/bots", name="handler")
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

            if ($botContainer->handle($token, $request)) {
                $dispatcher->dispatch(ActivityEvent::EVENT_NAME, new ActivityEvent($request, $token));
            }
        }

        return new Response('It works!! ☺');
    }
}
