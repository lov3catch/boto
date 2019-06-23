<?php

declare(strict_types=1);

namespace App\Events;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ActivityEvent extends Event
{
    public const EVENT_NAME = 'bots.activity';
    /**
     * @var Request
     */
    private $request;

    /**
     * @var BotHandlerInterface
     */
    private $handler;

    public function __construct(Request $request, BotHandlerInterface $handler)
    {
        $this->request = $request;
        $this->handler = $handler;
    }

    public function getRequestContent(): array
    {
        return json_decode($data = $this->request->getContent(), true);
    }

    /**
     * @return BotHandlerInterface
     */
    public function getHandler(): BotHandlerInterface
    {
        return $this->handler;
    }
}