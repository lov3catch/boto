<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\StopWordException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class StopWordChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $message = mb_strtolower($update->getMessage()->getText() ?? '');

        foreach ($setting->getStopWords() as $stopWord) {
            if (false === strpos($message, $stopWord)) continue;

            throw new StopWordException();
        }
    }
}