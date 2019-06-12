<?php

declare(strict_types=1);

namespace App\Events;

use Formapro\TelegramBot\Bot;
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
     * @var Bot
     */
    private $bot;

    public function __construct(Request $request, Bot $bot)
    {
        $this->request = $request;
        $this->bot = $bot;
    }

    public function getRequestContent(): array
    {
        return json_decode($data = $this->request->getContent(), true);
    }

    public function getBot(): Bot
    {
        return $this->bot;
    }
}