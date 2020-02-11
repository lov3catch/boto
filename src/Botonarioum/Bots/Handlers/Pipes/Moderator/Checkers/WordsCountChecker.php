<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\WordsCountException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class WordsCountChecker
{
    public function check(Update $update, ModeratorSetting $setting): void
    {
        $message = $update->getMessage()->getText() ?? '';

        if (count(explode(' ', $message)) > $setting->getMaxWordsCount()) {
            throw new WordsCountException();
        }
    }
}