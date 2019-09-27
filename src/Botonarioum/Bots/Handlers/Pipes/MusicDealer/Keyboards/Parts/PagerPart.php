<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\Parts;

use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\Update;
use Predis\Client;

class PagerPart
{
    /**
     * @var Client
     */
    private $redisStorage;

    public function __construct()
    {
        $this->redisStorage = new Client();
    }

    public function build(array &$keyboard, TrackFinderSearchResponse $response, Update $update)
    {
        $callbackData = $update->getCallbackQuery()->getData();

        // old callback schema example: pager.prev.limit.10.offset.0.track_name.text
        // new callback schema example: 11a38b9a-b3da-360f-9353-a5a725514269

        if ($this->isOldSchema($callbackData)) {
            $this->defaultDriver($keyboard, $response, $update);
        } else {
            //
        }
    }

    private function isOldSchema(string $callbackData): bool
    {
        $callbackDataAsArray = explode('.', $callbackData);

        return 8 === count($callbackDataAsArray);
    }

    private function defaultDriver(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
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

    private function redisDriver(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        //
    }
}