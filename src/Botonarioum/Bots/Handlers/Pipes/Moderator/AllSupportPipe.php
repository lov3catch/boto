<?php

declare(strict_types=1);


namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;


use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class AllSupportPipe extends AbstractPipe
{

    public function isSupported(Update $update): bool
    {
        return true;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        return true;
    }
}