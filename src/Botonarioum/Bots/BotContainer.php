<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots;

use App\Botonarioum\Bots\Handlers\BotHandlerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;

class BotContainer
{
    public $bots = [];

    public function add(string $token, BotHandlerInterface $handler): self
    {
        $this->bots[$token] = $handler;

        return $this;
    }

    public function handle(string $token, Request $request): ?BotHandlerInterface
    {
        $json = json_decode($request->getContent(), true);

        $handler = $this->bots[$token] ?? null;

        if ($handler instanceof BotHandlerInterface) {
            $handler->handle(new Bot($token), Update::create($json));

            return $handler;
        }

        return null;
    }
}