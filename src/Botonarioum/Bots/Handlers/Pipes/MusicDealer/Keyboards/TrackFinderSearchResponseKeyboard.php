<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards;

use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;

class TrackFinderSearchResponseKeyboard
{
    public function build(TrackFinderSearchResponse $response): InlineKeyboardMarkup
    {
        $keyboard = [];

        $this->attachContentPart($keyboard, $response);
        $this->attachPagerPart($keyboard, $response);


        return new InlineKeyboardMarkup($keyboard);
    }

    private function attachContentPart(array &$keyboard, TrackFinderSearchResponse $response): void
    {
        $keyboard = array_map(function (array $item) {
            return [InlineKeyboardButton::withCallbackData($item[0], 'data-here')];
        }, $response->getData());
    }

    private function attachPagerPart(array &$keyboard, TrackFinderSearchResponse $response): void
    {
        $paginationKeyboard = [];

        $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('◀️', 'prev');
        $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('▶️', 'next');

        $keyboard[] = $paginationKeyboard;
    }
}