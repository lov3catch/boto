<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use App\Botonarioum\Bots\Handlers\Pipes\DefaultPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\AllSupportPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\BlockAllGlobalPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\BlockAllPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\BlockPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DisableLinkPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\EnableLinkPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\GroupMessageEditPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\GroupMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\GroupMessageReplyPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\GroupSettingsPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\InfoPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\MyGroupsPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\NewChatMemberPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RemoveBotFromGroupPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\SettingsAwaitPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\SettingsCancelPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\SettingsChangerPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\SettingsGetterPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\StartPipe;
use App\Botonarioum\Bots\Handlers\Pipes\PipeInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;
use Psr\Log\LoggerInterface;

//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\BotonarioumPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DonatePipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\DownloadCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\MessagePipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\NextCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\PrevCallbackPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\StartPipe;
//use App\Botonarioum\Bots\Handlers\Pipes\Moderator\ShowGroupMenuPipe;

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
//    /**
//     * @var ShowGroupMenuPipe
//     */
//    private $showGroupMenuPipe;
    /**
     * @var GroupSettingsPipe
     */
    private $groupSettingsPipe;
//    /**
//     * @var MyGroupsPipe
//     */
//    private $myGroupsPipe;
    /**
     * @var SettingsGetterPipe
     */
    private $settingsGetterPipe;
    /**
     * @var SettingsChangerPipe
     */
    private $settingsChangerPipe;
    /**
     * @var SettingsAwaitPipe
     */
    private $settingsAwaitPipe;
    /**
     * @var SettingsCancelPipe
     */
    private $settingsCancelPipe;
    /**
     * @var InfoPipe
     */
    private $infoPipe;
    /**
     * @var GroupMessageReplyPipe
     */
    private $groupMessageReplyPipe;
    /**
     * @var AllSupportPipe
     */
    private $allSupportPipe;
    /**
     * @var RemoveBotFromGroupPipe
     */
    private $removeBotFromGroup;
    /**
     * @var DisableLinkPipe
     */
    private $disableLinkPipe;
    /**
     * @var EnableLinkPipe
     */
    private $enableLinkPipe;
    /**
     * @var BlockPipe
     */
    private $blockPipe;
    /**
     * @var BlockAllPipe
     */
    private $blockAllPipe;
    /**
     * @var BlockAllGlobalPipe
     */
    private $blockAllGlobalPipe;
    /**
     * @var GroupMessageEditPipe
     */
    private $groupMessageEditPipe;

    public function __construct(LoggerInterface $logger,
                                StartPipe $startPipe,
                                GroupMessagePipe $groupMessagePipe,
                                NewChatMemberPipe $newChatMemberPipe,
                                MyGroupsPipe $myGroupsPipe,
//                                ShowGroupMenuPipe $showGroupMenuPipe,
                                GroupSettingsPipe $groupSettingsPipe,
                                SettingsGetterPipe $settingsGetterPipe,
                                SettingsChangerPipe $settingsChangerPipe,
                                SettingsAwaitPipe $settingsAwaitPipe,
                                SettingsCancelPipe $settingsCancelPipe,
                                GroupMessageReplyPipe $groupMessageReplyPipe,
                                InfoPipe $infoPipe,
                                AllSupportPipe $allSupportPipe,
                                RemoveBotFromGroupPipe $removeBotFromGroupPipe,
                                DisableLinkPipe $disableLinkPipe,
                                EnableLinkPipe $enableLinkPipe,
                                BlockPipe $blockPipe,
                                BlockAllPipe $blockAllPipe,
                                BlockAllGlobalPipe $blockAllGlobalPipe,
                                GroupMessageEditPipe $groupMessageEditPipe)
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
//        $this->showGroupMenuPipe = $showGroupMenuPipe;
        $this->groupSettingsPipe = $groupSettingsPipe;
        $this->settingsGetterPipe = $settingsGetterPipe;
        $this->settingsChangerPipe = $settingsChangerPipe;
        $this->settingsAwaitPipe = $settingsAwaitPipe;
        $this->settingsCancelPipe = $settingsCancelPipe;
        $this->groupMessageReplyPipe = $groupMessageReplyPipe;
        $this->infoPipe = $infoPipe;
        $this->allSupportPipe = $allSupportPipe;
        $this->disableLinkPipe = $disableLinkPipe;
        $this->enableLinkPipe = $enableLinkPipe;
        $this->removeBotFromGroup = $removeBotFromGroupPipe;
        $this->blockPipe = $blockPipe;
        $this->blockAllPipe = $blockAllPipe;
        $this->blockAllGlobalPipe = $blockAllGlobalPipe;
        $this->groupMessageEditPipe = $groupMessageEditPipe;

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
//            ->add($this->allSupportPipe)
            ->add($this->startPipe)             // message
            ->add($this->disableLinkPipe)
            ->add($this->enableLinkPipe)
            ->add($this->myGroupsPipe)          // message
            ->add($this->groupSettingsPipe)     // callback

            ->add($this->blockPipe)
            ->add($this->blockAllPipe)
            ->add($this->blockAllGlobalPipe)
            ->add($this->removeBotFromGroup)
            ->add($this->groupMessageEditPipe)
            ->add($this->newChatMemberPipe)
            ->add($this->groupMessagePipe)


//            ->add($this->groupMessageReplyPipe)
            ->add($this->settingsGetterPipe)    // callback
            ->add($this->settingsChangerPipe)
            ->add($this->settingsCancelPipe)
            ->add($this->settingsAwaitPipe)
            ->add($this->infoPipe);
//            ->add($this->allSupportPipe);


        //            ->add($this->showGroupMenuPipe)
//            ->add($this->newChatMemberPipe)
//            ->add($this->groupMessagePipe);

//            ->add($this->donatePipe)
//            ->add($this->botonarioumPipe)
//            ->add($this->messagePipe)
//            ->add($this->prevCallbackPipe)
//            ->add($this->nextCallbackPipe)
//            ->add($this->downloadCallbackPipe)
//            ->add($this->defaultPipe);
    }
}