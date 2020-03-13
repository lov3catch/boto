<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Helpers;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Chat;
use Formapro\TelegramBot\User;
use Throwable;
use function Formapro\Values\set_values;

class IsChatAdministrator
{
    /**
     * @var Chat
     */
    private $chat;
    /**
     * @var Bot
     */
    private $bot;

    public function __construct(Bot $bot, Chat $chat)
    {
        $this->bot = $bot;
        $this->chat = $chat;
    }

    public function checkUser(User $user): bool
    {
//        return false;// todo: check this
        /** @var User $chatAdministrator */
        foreach ($this->getChatAdministrators() as $chatAdministrator) {
            if ($chatAdministrator->getId() === $user->getId()) return true;
        }

        return false;
    }

    public function checkBot(Bot $bot): bool
    {
        $me = (new GetMe())->me($bot);
//        $getMeUrl = implode('/', ['https://api.telegram.org', 'bot' . $this->bot->getToken(), 'getMe']);
//
//        $me = new User();
//        set_values($me, json_decode(file_get_contents($getMeUrl), true)['result']);

        /** @var User $chatAdministrator */
        foreach ($this->getChatAdministrators() as $chatAdministrator) {
            if (!$chatAdministrator instanceof User) continue;
            if ($chatAdministrator->getId() === $me->getId()) return true;
        }

        return false;
    }

    private function getChatAdministrators(): iterable
    {
//        try {
            $getChatAdministratorsUrl = implode('/', ['https://api.telegram.org', 'bot' . $this->bot->getToken(), 'getChatAdministrators?chat_id=' . $this->chat->getId()]);

            $adminsJson = json_decode(file_get_contents($getChatAdministratorsUrl), true)['result'] ?? [];
            foreach ($adminsJson as $adminJson) {
                $admin = new User();
                set_values($admin, $adminJson['user']);

                yield $admin;
            }
//        } catch (Throwable $exception) {
//            yield;
//        }


    }
}
