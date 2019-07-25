<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use App\Botonarioum\Bots\Handlers\Pipes\DefaultPipe;
use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\BotonarioumPipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DonatePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DownloadCallbackPipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\NextCallbackPipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\PrevCallbackPipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\StartPipe;
use App\Botonarioum\Bots\Handlers\Pipes\PipeInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class MusicDealerHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'bot.md.downloader';

    private $pipes = [];

    public function add(PipeInterface $pipe): AbstractHandler
    {
        $this->pipes[] = $pipe;

        return $this;
    }

    public function handle(Bot $bot, Update $update): bool
    {
        $this->init();

        foreach ($this->pipes as $pipe) {
            if ($pipe->handle($bot, $update)) break;
        }

        return true;
    }

    private function init(): void
    {
        $this
            ->add(new StartPipe())
            ->add(new DonatePipe())
            ->add(new BotonarioumPipe())
            ->add(new MessagePipe())
            ->add(new NextCallbackPipe())
            ->add(new PrevCallbackPipe())
            ->add(new DownloadCallbackPipe())
            ->add(new DefaultPipe());
    }
}