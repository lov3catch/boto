<?php

declare(strict_types=1);

namespace App\Events;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Symfony\Component\EventDispatcher\Event;

class SpamDetectedEvent extends Event
{
    public const EVENT_NAME = 'bots.group.spam';
    /**
     * @var Update
     */
    private $update;
    /**
     * @var Bot
     */
    private $bot;

    public function __construct(Update $update, Bot $bot)
    {
        $this->update = $update;
        $this->bot = $bot;
    }

    public function getUpdate(): Update
    {
        return $this->update;
    }

    public function getBot(): Bot
    {
        return $this->bot;
    }
}