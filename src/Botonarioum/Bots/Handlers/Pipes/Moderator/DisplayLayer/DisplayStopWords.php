<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DisplayLayer;

use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Chat;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class DisplayStopWords
{
    private const CHUNK_SIZE = 500;

    /**
     * @var Bot
     */
    private $bot;

    /**
     * @var ModeratorSetting
     */
    private $settings;

    public function __construct(Bot $bot, ModeratorSetting $setting)
    {
        $this->bot = $bot;
        $this->settings = $setting;
    }

    /**
     * @param Chat $chat
     * @param Update $update
     */
    public function display(Chat $chat, Update $update): void
    {
        /**
         * @var string[] $stopWords
         */
        $stopWords = $this->settings->getStopWords();

        foreach (array_chunk($stopWords, self::CHUNK_SIZE) as $partOfStopWords) {
            $message = 'Список стоп-слов' . PHP_EOL . 'Текущее значение: ' . PHP_EOL . implode(', ', $partOfStopWords) . '.';

            $callbackData = str_replace(':set:', ':change:', $update->getCallbackQuery()->getData());

            $sendMessage = new SendMessage($chat->getId(), $message);
            $sendMessage->setReplyMarkup(new InlineKeyboardMarkup([[InlineKeyboardButton::withCallbackData('Изменить', $callbackData)]]));

            $this->bot->sendMessage($sendMessage);
        }
    }
}