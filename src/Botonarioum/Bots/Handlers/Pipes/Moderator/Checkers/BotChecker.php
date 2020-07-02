<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BotException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class BotChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $member = $update->getMessage()->getNewChatMember();
//        $member = (new MessageDTO($update->getMessage()))->getNewChatMember();

        if ($member->isBot()) throw new BotException();
    }
}