<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\SandboxBot;

use App\Botonarioum\Bots\AbstractBot;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class SandboxBot extends AbstractBot
{
    protected const ENV_TOKEN_KEY = 'SANDBOXBOT_TOKEN';

    private const
        CONTACTS_KEY = 'ℹ️ Контакты',
        BOTS_CATALOGUE_KEY = '📔 Боты',
        GROUPS_CATALOGUE_KEY = '📔 Групы';

    /**
     * @var Bot
     */
    private $bot;

    public function __construct()
    {
        $this->bot = new Bot($this->getToken());
    }

    private function defaultKeyboard(): ReplyKeyboardMarkup
    {
        return new ReplyKeyboardMarkup([[new KeyboardButton(self::BOTS_CATALOGUE_KEY), new KeyboardButton(self::GROUPS_CATALOGUE_KEY)], [new KeyboardButton(self::DONATE_KEY), new KeyboardButton(self::CONTACTS_KEY)]]);
    }

    public function handle(Update $update): bool
    {
        $userInput = $update->getMessage()->getText();

        if ($userInput === self::CONTACTS_KEY) {
            $message = $this->contactAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());
        } elseif ($userInput === self::DONATE_KEY) {
            $message = $this->donateAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());;
        } elseif ($userInput === self::BOTS_CATALOGUE_KEY) {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                '
Список ботов:
@zaycev_net_music_bot
(Бот для поиска музыки. Статус: забанено на iOS устройствах)
@deezer_music_bot
(Бот для поиска музыки. Статус: активен)
@pied_piper_bot
(Бот для поиска музыки. Статус: активен)
@equalizerguru_bot
(Бот для поиска музыки. Статус: активен)
                '
            );

            $message->setReplyMarkup($this->defaultKeyboard());
        } elseif ($userInput === self::GROUPS_CATALOGUE_KEY) {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                '
Список груп:
https://t.me/mp3db
(Большое собрание музыки. Более 150 тыс. записей)

https://t.me/vyrvano_kontekst
(Цитатник женского коллектива)
'
            );

            $message->setReplyMarkup($this->defaultKeyboard());
        } else {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Выбери что-то из меню :)'
            );

            $message->setReplyMarkup($this->defaultKeyboard());
        }

        $this->bot->sendMessage($message);

        return true;
    }

    public function getToken(): string
    {
        return $_ENV[self::ENV_TOKEN_KEY];
    }
}