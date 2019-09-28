<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Helpers;

use Formapro\TelegramBot\Update;

class UpdateHelper
{
    /**
     * @var Update
     */
    private $update;

    public function __construct(Update $update)
    {
        $this->update = $update;
    }

    public function getText(): string
    {
        $text = $this->update->getCallbackQuery()
            ? explode('.', $this->update->getCallbackQuery()->getData())[7]
            : $this->update->getMessage()->getText();

        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
    }
}