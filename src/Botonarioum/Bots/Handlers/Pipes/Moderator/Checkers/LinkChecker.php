<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class LinkChecker
{
    protected const LINK_PATTERNS = ['@', 'http:', 'https:', 'http://', 'https://', 't.me', 'www', '.ком'];
    protected const LINK_REGEX = '/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/';

    public function check(Update $update, ModeratorSetting $setting): void
    {
        if (true === $setting->getAllowLink()) return;

        $message = $update->getMessage()->getText() ?? '';
        $caption = (new MessageDTO($update->getMessage()))->getCaption() ?? '';

        $this->doCheck($message);
        $this->doCheck($caption);
    }

    public function doCheck(string $text): void
    {
        foreach (self::LINK_PATTERNS as $linkPattern) {
            if (strpos($text, $linkPattern) !== false) {
                throw new LinkException();
            }
        }

        $matches = [];

        $hasLink = preg_match_all(self::LINK_REGEX, $text, $matches);

        if (false === $hasLink) return;

        if (0 === $hasLink) return;

        throw new LinkException();
    }
}