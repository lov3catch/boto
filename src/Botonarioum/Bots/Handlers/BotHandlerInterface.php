<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

interface BotHandlerInterface
{
    public function handle(Bot $bot, Update $update): bool;
}
