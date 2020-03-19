<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\Element;
use App\Entity\ModeratorGroup;
use App\Entity\ModeratorOwner;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

//use App\Entity\ModeratorGroupOwners;

class GroupMenuPipe extends CallbackPipe
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

//        $groupOwnersRepository = $this->em->getRepository(ModeratorOwner::class);
//
//        $myGroups = $groupOwnersRepository->findBy(['user_id' => $update->getCallbackQuery()->getFrom()->getId(), 'is_active' => true]);
//
//        if ([] === $myGroups) {
//            $bot->sendMessage(new SendMessage(
//                $update->getCallbackQuery()->getChat()->getId(),
//                'У вас еще нет групп.'
//            ));
//
//            return true;
//        }
//
//        $groupInfoRepository = $this->em->getRepository(ModeratorGroup::class);
//
//        $myGroupsInfo = $groupInfoRepository->findBy([
//            'group_id' => array_map(function (ModeratorOwner $group) {
//                return $group->getGroupId();
//            }, $myGroups)]);

        $keyboard = [];
//        /** @var ModeratorGroup $myGroupInfo */
//        foreach ($myGroupsInfo as $myGroupInfo) {

        $groupId = array_reverse(explode(':', $update->getCallbackQuery()->getData()))[0];
//        var_dump($groupId);die;

//        $groupId = $update->getCallbackQuery()

            $settingsCallbackData = implode(':', ['group', 'settings', 'get', $groupId]);
            $blockListCallbackData = implode(':', ['group', 'blocklist', 'get', $groupId]);
            $keyboard[] = [
//                InlineKeyboardButton::withCallbackData(ucfirst($myGroupInfo->getGroupTitle()), $callbackData),
                InlineKeyboardButton::withCallbackData('⚙️ Настройки', $settingsCallbackData),
                InlineKeyboardButton::withCallbackData('🚫 Баны', $blockListCallbackData)];
//        }

        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
//            $update->getMessage()->getChat()->getId(),
            'Меню группы:'
        );


        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'menu', 'get']))) return false;

        return true;
        return parent::isSupported($update) && $update->getMessage()->getText() === StartPipe::GROUPS_KEY;
    }
}