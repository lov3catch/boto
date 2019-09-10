<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

interface PipeInterface
{
    public function handle(Bot $bot, Update $update): bool;

    public function isSupported(Update $update): bool;

    public function processing(Bot $bot, Update $update): bool;
}