<?php declare(strict_types=1);

namespace App\Botonarioum\Bots;

use Formapro\TelegramBot\Update;

interface BotInterface
{
    public function handle(Update $update): bool;

    public function getToken(): string;

    public function isCurrentBot(): bool;
}
