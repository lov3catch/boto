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

    public function handle(Request $request): void
    {
        $data = $request->request->all();

        foreach ($this->bots as $bot) {
            if ($bot->isCurrentBot($request)) {
                $bot->handle(Update::create($data));
                break;
            }
        }
    }
}