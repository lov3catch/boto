<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\Parts;

use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\Update;

class ContentPart
{
    public function build(array &$keyboard, TrackFinderSearchResponse $response, Update $update)
    {
        $keyboard = array_merge($keyboard, array_map(function (array $item) {
            $title = $item[0];
            $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
            // todo: Реализовать класс для работы с провайдерами
            $callbackData = implode('::', ['zn', $item[1]]);
            return [InlineKeyboardButton::withCallbackData($title, $callbackData)];
        }, $response->getData()));
    }
}