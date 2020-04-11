<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\ChatMember;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;

class LeftChatMemberPipe extends AbstractPipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        try {
            $deleteMessage = new DeleteMessage(
                $update->getMessage()->getChat()->getId(),
                $update->getMessage()->getMessageId());

            $bot->deleteMessage($deleteMessage);
        } catch (\Exception $exception) {
            //
        }

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (!$update->getMessage() instanceof Message) return false;

        return ($update->getMessage()->getLeftChatMember() instanceof ChatMember);
    }
}