<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use App\Botonarioum\Bots\Handlers\Pipes\DefaultPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\BotonarioumPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DonatePipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DownloadCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\MessagePipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\NextCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\PrevCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\StartPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\GroupMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\MyGroupsPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\NewChatMemberPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\StartPipe;
use App\Botonarioum\Bots\Handlers\Pipes\PipeInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Psr\Log\LoggerInterface;

class ModeratorHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'bot.group.moderator';

    private $pipes = [];

    private $logger;

    /**
     * @var StartPipe
     */
    private $startPipe;

    /**
     * @var BotonarioumPipe
     */
    private $botonarioumPipe;

    /**
     * @var MessagePipe
     */
    private $messagePipe;

    /**
     * @var NextCallbackPipe
     */
    private $nextCallbackPipe;

    /**
     * @var PrevCallbackPipe
     */
    private $prevCallbackPipe;

    /**
     * @var DownloadCallbackPipe
     */
    private $downloadCallbackPipe;

    /**
     * @var DefaultPipe
     */
    private $defaultPipe;

    /**
     * @var DonatePipe
     */
    private $donatePipe;

    /**
     * @var GroupMessagePipe
     */
    private $groupMessagePipe;
    /**
     * @var NewChatMemberPipe
     */
    private $newChatMemberPipe;
    /**
     * @var MyGroupsPipe
     */
    private $myGroupsPipe;

    public function __construct(LoggerInterface $logger,
                                StartPipe $startPipe,
                                GroupMessagePipe $groupMessagePipe,
                                NewChatMemberPipe $newChatMemberPipe,
                                MyGroupsPipe $myGroupsPipe)
//                                DonatePipe $donatePipe,
//                                BotonarioumPipe $botonarioumPipe,
//                                MessagePipe $messagePipe,
//                                NextCallbackPipe $nextCallbackPipe,
//                                PrevCallbackPipe $prevCallbackPipe,
//                                DownloadCallbackPipe $downloadCallbackPipe,
//                                DefaultPipe $defaultPipe)
    {
        $this->logger = $logger;
        $this->startPipe = $startPipe;
        $this->groupMessagePipe = $groupMessagePipe;
        $this->newChatMemberPipe = $newChatMemberPipe;
        $this->myGroupsPipe = $myGroupsPipe;
//        $this->donatePipe = $donatePipe;
//        $this->botonarioumPipe = $botonarioumPipe;
//        $this->messagePipe = $messagePipe;
//        $this->nextCallbackPipe = $nextCallbackPipe;
//        $this->prevCallbackPipe = $prevCallbackPipe;
//        $this->downloadCallbackPipe = $downloadCallbackPipe;
//        $this->defaultPipe = $defaultPipe;
    }


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
            ->add($this->startPipe)
            ->add($this->myGroupsPipe)
            ->add($this->newChatMemberPipe)
            ->add($this->groupMessagePipe);

//            ->add($this->donatePipe)
//            ->add($this->botonarioumPipe)
//            ->add($this->messagePipe)
//            ->add($this->prevCallbackPipe)
//            ->add($this->nextCallbackPipe)
//            ->add($this->downloadCallbackPipe)
//            ->add($this->defaultPipe);
    }
}