<?php

declare(strict_types=1);

namespace App\Events;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use Symfony\Component\EventDispatcher\Event;
use Formapro\TelegramBot\Update;

class ActivityEvent extends Event
{
    public const EVENT_NAME = 'bots.activity';
    /**
     * @var Update
     */
    private $update;

    /**
     * @var BotHandlerInterface
     */
    private $handler;

    public function __construct(Update $update, BotHandlerInterface $handler)
    {
        $this->update = $update;
        $this->handler = $handler;
    }

    public function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * @return BotHandlerInterface
     */
    public function getHandler(): BotHandlerInterface
    {
        return $this->handler;
    }
}