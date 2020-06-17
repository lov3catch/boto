<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers;

use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\EntityDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Update;

class LinkChecker
{
    protected const LINK_PATTERNS = ['@', 'http:', 'https:', 'http://', 'https://', 't.me', 'www', '.ком', '.com', '.ua', '.ru', '.kz', '.se'];
    protected const LINK_REGEX = '/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&\'\(\)\*\+,;=.]+$/';

    public function check(Update $update, ModeratorSetting $setting): void
    {
        if (true === $setting->getAllowLink()) return;

        /**
         * @var EntityDTO $entity
         */
        foreach ((new MessageDTO($update->getEditedMessage()))->getCaptionEntities() as $captionEntity) {
            if ('url' === $captionEntity->getType()) {
                throw new LinkException();
            }
        }

        /**
         * @var EntityDTO $entitie
         */
        foreach ((new MessageDTO($update->getEditedMessage()))->getEntities() as $entity) {
            if ('url' === $entity->getType()) {
                throw new LinkException();
            }
        }
    }

    public function doCheck(string $text): bool
    {
        foreach (self::LINK_PATTERNS as $linkPattern) {
            if (strpos($text, $linkPattern) !== false) {
                return true;
            }
        }

        foreach (explode(' ', $text) as $txt) {

            $hasLink = preg_match_all(self::LINK_REGEX, $txt);

            if (false === $hasLink) return false;

            if (0 === $hasLink) return false;

            return true;
        }

        return false;
    }
}
