<?php

declare(strict_types=1);

namespace App\Events;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Symfony\Component\EventDispatcher\Event;

class AddedUserInGroupEvent extends Event
{
    public const EVENT_NAME = 'bots.activity.add.user';
    /**
     * @var Update
     */
    private $update;

//    /**
//     * @var BotHandlerInterface
//     */
//    private $handler;
    /**
     * @var Bot
     */
    private $bot;

    public function __construct(Update $update, Bot $bot)
    {
        $this->update = $update;
        $this->bot = $bot;
//        $this->handler = $handler;
    }

    public function getUpdate(): Update
    {
        return $this->update;
    }

    public function getBot(): Bot
    {
        return $this->bot;
    }

//    /**
//     * @return BotHandlerInterface
//     */
//    public function getHandler(): BotHandlerInterface
//    {
//        return $this->handler;
//    }
}