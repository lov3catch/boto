<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\RepostException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\Update;
use function Formapro\Values\get_values;

class ForwardChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        if (!$update->getEditedMessage() instanceof Message) return;

        if ($setting->getAllowForward() === true) return;

        $messageData = get_values($update->getEditedMessage());

        if (isset($messageData['forward_from_chat'])) {
            throw new RepostException();
        }
    }
}