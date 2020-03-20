<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorMember;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

//use App\Entity\ModeratorGroupOwners;

class GroupGetBlockListPipe extends CallbackPipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        // todo: вставить проверку является ли пользователь админом\хозяином группы

        $groupOwnersRepository = $this->em->getRepository(ModeratorBlock::class);
//
        $blockList = $groupOwnersRepository->findBy(['admin_id' => $update->getCallbackQuery()->getFrom()->getId()], ['created_at' => 'DESC']);
//
        if ([] === $blockList) {
            $bot->sendMessage(new SendMessage(
                $update->getCallbackQuery()->getMessage()->getChat()->getId(),
                'Список пуст.'
            ));

            return true;
        }

        $keyboard = [];

//        $blockedUserIds = array_map(function (ModeratorBlock $block){return $block->getUserId();}, $blockList);
//        $blockedUserDetails = $this->em->getRepository(ModeratorMember::class)->findBy(['member_id' => $blockedUserIds]);



        /**
         * @var $block ModeratorBlock
         */
        foreach ($blockList as $block) {
            // todo: оптимизировать получение детальной информации. Выше мы получаем всех забаненых пользователей. Получать детальную инфу по айдишникам и сливать в один массив

            /** @var ModeratorMember $userDetail */
            $userDetail =$this->em->getRepository(ModeratorMember::class)->findOneBy(['member_id' => $block->getUserId()]);

            $unblockCallbackData = implode(':', ['group', 'blocklist', 'info', $block->getId()]);

            if ($userDetail instanceof ModeratorMember) {
                $firstName = $userDetail->getMemberFirstName();
                $userName = $userDetail->getMemberUsername();
            } else {
                $firstName = $block->getUserId();
                $userName = '';
            }

            $title = "$firstName $userName";

            $keyboard[] = [InlineKeyboardButton::withCallbackData($title, $unblockCallbackData)];
        }

        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'Список заблокированных пользователей:'
        );

        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'blocklist', 'get']))) return false;

        return true;
    }
}