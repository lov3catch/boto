<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class LinkChecker
{
    private const LINK_PATTERNS = ['@', 'http:', 'https:', 'http://', 'https://'];

    public function check(Update $update, ModeratorSetting $setting): void
    {
//        var_dump($setting->getAllowLink());die;
//        var_dump('$expression');die;
        if (true === $setting->getAllowLink()) return;

        $message = $update->getMessage()->getText() ?? '';

        foreach (self::LINK_PATTERNS as $linkPattern) {
            if (strpos($message, $linkPattern) !== false) {
                throw new LinkException();
            }
        }
    }

}