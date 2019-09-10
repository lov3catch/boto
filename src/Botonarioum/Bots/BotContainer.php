<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use App\Events\ActivityEvent;
use Exception;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class BotContainer
{
    /**
     * @var BotHandlerInterface[]
     */
    public $bots = [];

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function add(string $token, BotHandlerInterface $handler): self
    {
        $this->bots[$token] = $handler;

        return $this;
    }

    public function handle(string $token, Request $request): void
    {
        try {
            $json = json_decode($request->getContent(), true);

            $handler = $this->bots[$token] ?? null;

            if ($handler instanceof BotHandlerInterface) {
                $handler->handle(new Bot($token), Update::create($json));

                $this->dispatcher->dispatch(ActivityEvent::EVENT_NAME, new ActivityEvent(Update::create($json), $handler));
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

    }
}