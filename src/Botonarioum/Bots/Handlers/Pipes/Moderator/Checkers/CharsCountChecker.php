<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\CharsCountException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class CharsCountChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $message = $update->getMessage()->getText() ?? '';

        if (mb_strlen($message) > $setting->getMaxMessageCharsCount()) {
            throw new CharsCountException();
        }

        $messageDTO = new MessageDTO($update->getMessage());

        $caption = $messageDTO->getCaption() ?? '';

        if (mb_strlen($caption) > $setting->getMaxMessageCharsCount()) {
            throw new CharsCountException();
        }
    }
}