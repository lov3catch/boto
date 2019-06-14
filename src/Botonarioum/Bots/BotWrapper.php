<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class BotWrapper
{
    /**
     * @var BotHandlerInterface
     */
    private $handler;

    /**
     * @var string
     */
    private $token;

    public function __construct(string $token, BotHandlerInterface $handler)
    {
        $this->handler = $handler;
        $this->token = $token;
    }

    public function handle(Update $update): void
    {
        $this->handler->handle(new Bot($this->token), $update);
    }
}
