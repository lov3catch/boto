<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards;

use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\Update;

class TrackFinderSearchResponseKeyboard
{
    public function build(TrackFinderSearchResponse $response, Update $update): InlineKeyboardMarkup
    {
        $keyboard = [];

        $this->attachPagerPart($keyboard, $response, $update);
        $this->attachContentPart($keyboard, $response, $update);
        $this->attachPagerPart($keyboard, $response, $update);


        return new InlineKeyboardMarkup($keyboard);
    }

    private function attachContentPart(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        $keyboard = array_merge($keyboard, array_map(function (array $item) {
            $title = $item[0];
            $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
            // todo: Реализовать класс для работы с провайдерами
            $callbackData = implode('::', ['zn', $item[1]]);
            return [InlineKeyboardButton::withCallbackData($title, $callbackData)];
        }, $response->getData()));
    }

    private function attachPagerPart(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        $paginationKeyboard = [];

        $text = $update->getCallbackQuery()
            ? explode('.', $update->getCallbackQuery()->getData())[7]
            : $update->getMessage()->getText();

        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        if ($response->getPager()->hasPrev()) {
            $prevCallbackData = implode('.', ['pager', 'prev', 'limit', $response->getPager()->limit(), 'offset', $response->getPager()->offset(), 'track_name', $text]);
            $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('◀️', $prevCallbackData);
        }

        if ($response->getPager()->hasNext()) {
            $nextCallbackData = implode('.', ['pager', 'next', 'limit', $response->getPager()->limit(), 'offset', $response->getPager()->offset(), 'track_name', $text]);
            $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('▶️', $nextCallbackData);
        }

        $keyboard[] = $paginationKeyboard;
    }
}