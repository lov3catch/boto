<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\Parts;

use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\Update;

class PagerPart
{
    public function build(array &$keyboard, TrackFinderSearchResponse $response, Update $update)
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