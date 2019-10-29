<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards;

use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Helpers\CallbackQueryHelper;
use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\Update;
use Rhumsaa\Uuid\Uuid;

class TrackFinderSearchResponseKeyboard
{
    /**
     * @var CallbackQueryHelper
     */
    private $callbackQueryHelper;
    /**
     * @var RedisStorage
     */
    private $storage;

    public function __construct(CallbackQueryHelper $callbackQueryHelper, RedisStorage $storage)
    {
        $this->callbackQueryHelper = $callbackQueryHelper;
        $this->storage = $storage;
    }

    public function build(TrackFinderSearchResponse $response, Update $update): InlineKeyboardMarkup
    {
        $pagerPart = $this->attachPagerPart($response, $update);
        $contentPart = $this->attachContentPart($response, $update);

        $keyboard = [];
        $keyboard[] = $pagerPart;
        $keyboard = array_merge($keyboard, $contentPart);
        $keyboard[] = $pagerPart;

//        var_dump($keyboard);die;

        return new InlineKeyboardMarkup($keyboard);

        $this->attachPagerPart($keyboard, $response, $update);
        $this->attachContentPart($keyboard, $response, $update);
        $this->attachPagerPart($keyboard, $response, $update);


        return new InlineKeyboardMarkup($keyboard);
    }

    private function attachContentPart(TrackFinderSearchResponse $response, Update $update): array
    {
        return array_map(function (array $item) {
            $title = $item[0];
            $title = mb_convert_encoding($title, 'UTF-8', 'UTF-8');
            // todo: Реализовать класс для работы с провайдерами
//            $callbackData = implode('::', ['zn', $item[1]]);
            return [InlineKeyboardButton::withCallbackData($title, $item[1])];
        }, $response->getData());
    }

    private function attachPagerPart(TrackFinderSearchResponse $response, Update $update): array
    {
        $paginationKeyboard = [];

//        $text = $update->getCallbackQuery()
//            ? explode('.', $update->getCallbackQuery()->getData())[7]
//            : $update->getMessage()->getText();
//
//        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        $text = $this->callbackQueryHelper->getTextFromCallback($update);

        // если есть ключ редиса - ставим время жизни 5 минут (тк он нам нужен всего на один запрос)
        if ($update->getCallbackQuery()) {
//            $oldSchema = (bool)(explode('.', $update->getCallbackQuery()->getData())[7] ?? null);
            if ($this->storage->client()->exists($update->getCallbackQuery()->getData())) {

                $this->storage->client()->expire($update->getCallbackQuery()->getData(), 60 * 5);
            }
        }

        if ($response->getPager()->hasPrev()) {
            $prevCallbackData = $this->callbackQueryHelper->buildPrevCallbackData(
                $response->getPager()->limit(),
                $response->getPager()->offset(),
                $text,
                (Uuid::uuid1())->toString());

            $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('◀️', $prevCallbackData);
        }

        if ($response->getPager()->hasNext()) {
            $nextCallbackData = $this->callbackQueryHelper->buildNextCallbackData(
                $response->getPager()->limit(),
                $response->getPager()->offset(),
                $text,
                (Uuid::uuid1())->toString());

            $paginationKeyboard[] = InlineKeyboardButton::withCallbackData('▶️', $nextCallbackData);
        }

        return $paginationKeyboard;

        $keyboard[] = $paginationKeyboard;
    }
}