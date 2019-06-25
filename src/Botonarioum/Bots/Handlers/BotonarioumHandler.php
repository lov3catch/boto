<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BotonarioumHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'bot.botonarioum.catalogue';

    private const
        CONTACTS_KEY = 'ℹ️ Контакты',
        BOTS_CATALOGUE_KEY = '📔 Боты',
        GROUPS_CATALOGUE_KEY = '📔 Групы';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function defaultKeyboard(): ReplyKeyboardMarkup
    {
        return new ReplyKeyboardMarkup([[new KeyboardButton(self::BOTS_CATALOGUE_KEY), new KeyboardButton(self::GROUPS_CATALOGUE_KEY)], [new KeyboardButton(self::DONATE_KEY), new KeyboardButton(self::CONTACTS_KEY)]]);
    }

    public function handle(Bot $bot, Update $update): bool
    {
        $userInput = $update->getMessage()->getText();

        if ($userInput === self::CONTACTS_KEY) {
            $message = $this->contactAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());
        } elseif ($userInput === self::DONATE_KEY) {
            $message = $this->donateAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());;
        } elseif ($userInput === self::BOTS_CATALOGUE_KEY) {
            $message = new SendMessage($update->getMessage()->getChat()->getId(), ' Список ботов:');

            $message->setReplyMarkup($this->buildKeyboardWithBots());
        } elseif ($userInput === self::GROUPS_CATALOGUE_KEY) {
            $message = new SendMessage($update->getMessage()->getChat()->getId(), ' Список груп:');

            $message->setReplyMarkup($this->buildKeyboardWithChannels());
        } else {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Выбери что-то из меню :)'
            );

            $message->setReplyMarkup($this->defaultKeyboard());
        }

        $bot->sendMessage($message);

        return true;
    }

    private function buildKeyboardWithBots()
    {
//        https://t.me/zaycev_net_music_bot
//        (Бот для поиска музыки. Статус: забанено на iOS устройствах)
//https://t.me/deezer_music_bot
//(Бот для поиска музыки. Статус: активен)
//https://t.me/pied_piper_bot
//(Бот для поиска музыки. Статус: активен)
//https://t.me/equalizerguru_bot
//(Бот для поиска музыки. Статус: активен)
        return new InlineKeyboardMarkup([[InlineKeyboardButton::withUrl('Zaycev Net', 'https://t.me/equalizerguru_bot')]]);
    }

    private function buildKeyboardWithChannels()
    {
//        https://t.me/mp3db
//        (Большое собрание музыки. Более 150 тыс. записей)
//
//https://t.me/vyrvano_kontekst
//(Цитатник женского коллектива)
        return new InlineKeyboardMarkup([[InlineKeyboardButton::withUrl('MP3', 'https://t.me/vyrvano_kontekst')]]);
    }
}