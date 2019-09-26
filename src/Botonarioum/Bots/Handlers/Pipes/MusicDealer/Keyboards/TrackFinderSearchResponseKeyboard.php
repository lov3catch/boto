<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards;

use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\Parts\ContentPart;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\Parts\PagerPart;
use App\Botonarioum\TrackFinder\TrackFinderSearchResponse;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\Update;

class TrackFinderSearchResponseKeyboard
{
    /**
     * @var ContentPart
     */
    private $contentPartBuilder;

    /**
     * @var PagerPart
     */
    private $pagerPartBuilder;

    public function __construct()
    {
        $this->contentPartBuilder = new ContentPart();
        $this->pagerPartBuilder = new PagerPart();
    }

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
        $this->contentPartBuilder->build($keyboard, $response, $update);
    }

    private function attachPagerPart(array &$keyboard, TrackFinderSearchResponse $response, Update $update): void
    {
        $this->pagerPartBuilder->build($keyboard, $response, $update);
    }
}