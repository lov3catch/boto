<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Helpers;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\User;
use function Formapro\Values\set_values;

class GetMe
{
    public function me(Bot $bot): User
    {
        $getMeUrl = implode('/', ['https://api.telegram.org', 'bot' . $bot->getToken(), 'getMe']);

        $me = new User();
        set_values($me, json_decode(file_get_contents($getMeUrl), true)['result']);

        return $me;
    }
}