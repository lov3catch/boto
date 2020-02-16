<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Helpers;

class RedisKeys
{
    public static function makeLastGreetingMessageIdKey(int $chatId): string
    {
        return implode(':', ['moderator', 'last_greeting', $chatId]);
    }

    public static function makeAwaitSettingChangeKey(int $chatId): string
    {
        return implode(':', ['moderator', 'group', 'settings', 'await', $chatId]);
    }
}