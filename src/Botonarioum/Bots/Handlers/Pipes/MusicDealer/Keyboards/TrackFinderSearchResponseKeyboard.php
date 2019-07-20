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

        $this->attachContentPart($keyboard, $response, $update);
        $this->attachPagerPart($keyboard, $response, $update);


        return new InlineKeyboardMarkup($keyboard);
    }

    private function attachContentPart(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        $keyboard = array_map(function (array $item) {
            return [InlineKeyboardButton::withCallbackData($item[0], 'data-here')];
        }, $response->getData());
    }

    private function attachPagerPart(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        $paginationKeyboard = [];

        $text = $update->getCallbackQuery()->getMessage()->getText() ?? $update->getMessage()->getText();

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