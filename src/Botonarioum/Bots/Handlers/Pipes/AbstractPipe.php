<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

abstract class AbstractPipe implements PipeInterface
{
    public function handle(Bot $bot, Update $update): bool
    {
        if ($this->isSupported($update)) return $this->processing($bot, $update);

        return false;
    }
}