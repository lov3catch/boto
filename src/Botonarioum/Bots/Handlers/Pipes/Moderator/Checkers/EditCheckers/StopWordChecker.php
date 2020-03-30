<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\StopWordException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class StopWordChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $message = $update->getEditedMessage()->getText() ?? '';

        foreach ($setting->getStopWords() as $stopWord) {
            if (false === strpos($message, $stopWord)) continue;

            throw new StopWordException();
        }

        $messageDTO = new MessageDTO($update->getEditedMessage());

        $caption = mb_strtolower($messageDTO->getCaption() ?? '');

        foreach ($setting->getStopWords() as $stopWord) {
            if (false === strpos($caption, $stopWord)) continue;

            throw new StopWordException();
        }
    }
}