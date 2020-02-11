<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\EntityDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class CommandPipe extends AbstractPipe
{

    public function isSupported(Update $update): bool
    {
        if (!$update->getMessage()) return false;

        $messageDTO = new MessageDTO($update->getMessage());

        /** @var EntityDTO $entity */
        foreach ($messageDTO->getEntities() as $entity) {
            if ('bot_command' === $entity->getType()) return true;
        }

        return false;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $bot->sendMessage(new SendMessage($update->getMessage()->getChat()->getId(), 'COMMAND'));

        return true;
    }
}