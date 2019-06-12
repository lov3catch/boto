<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots;

use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;

class BotContainer
{
    private $bots = [];

    public function add(BotInterface $bot): self
    {
        $this->bots[] = $bot;

        return $this;
    }

    public function handle(Request $request): ?BotInterface
    {
        $json = json_decode($request->getContent(), true);

        foreach ($this->bots as $bot) {
            if ($bot->isCurrentBot($request)) {
                $bot->handle(Update::create($json));
                return $bot;
            }
        }

        return null;
    }
}