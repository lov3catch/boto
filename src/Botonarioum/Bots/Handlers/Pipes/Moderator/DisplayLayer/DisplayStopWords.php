<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator\DisplayLayer;

use App\Entity\ModeratorSetting;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Chat;
use Formapro\TelegramBot\SendMessage;

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
     */
    public function display(Chat $chat): void
    {
        /**
         * @var string[] $stopWords
         */
        $stopWords = $this->settings->getStopWords();

        foreach (array_chunk($stopWords, self::CHUNK_SIZE) as $partOfStopWords) {
            $message = 'Список стоп-слов' . PHP_EOL . 'Текущее значение: ' . PHP_EOL . implode(', ', $partOfStopWords) . '.';

            $this->bot->sendMessage(new SendMessage($chat->getId(), $message));
        }
    }
}