<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\CharsCountException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class CharsCountChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $message = $update->getEditedMessage()->getText() ?? '';

        if (mb_strlen($message) > $setting->getMaxMessageCharsCount()) {
            throw new CharsCountException();
        }
    }
}