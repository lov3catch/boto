<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

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

class MyGroupsPipe extends MessagePipe
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
        $groupOwnersRepository = $this->em->getRepository(ModeratorOwner::class);

        $myGroups = $groupOwnersRepository->findBy(['user_id' => $update->getMessage()->getFrom()->getId()]);

        if ([] === $myGroups) {
            $bot->sendMessage(new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'У вас еще нет групп.'
            ));

            return true;
        }

//        var_dump($myGroups);die;

        $groupInfoRepository = $this->em->getRepository(ModeratorGroup::class);

        $myGroupsInfo = $groupInfoRepository->findBy([
            'group_id' => array_map(function (ModeratorOwner $group) {
                return $group->getGroupId();
            }, $myGroups)]);

//        var_dump($myGroupsInfo);die;

        $keyboard = [];
        /** @var ModeratorGroup $myGroupInfo */
        foreach ($myGroupsInfo as $myGroupInfo) {
            $callbackData = implode(':', ['group', 'settings', 'get', $myGroupInfo->getGroupId()]);
            $keyboard[] = [InlineKeyboardButton::withCallbackData(ucfirst($myGroupInfo->getGroupTitle()), $callbackData), InlineKeyboardButton::withCallbackData('⚙️ Настройки', $callbackData)];
        }

//        $markup = new InlineKeyboardMarkup($keyboard);
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Вот список ваших групп: (всего ' . count($myGroupsInfo) . ' штук).'
        );
        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

        return true;


        // todo: complete me!

        // добавить таблицу GroupData либо писать в JSON
//        $groups = $this->em->getRepository(ModeratorGroupOwners::class)->findBy(['partner_id' => $update->getMessage()->getFrom()->getId()]);

        $groupIds = array_map(function (ModeratorGroupOwners $groupOwners) {
            return $groupOwners->getGroupId();
        }, $this->em->getRepository(ModeratorGroupOwners::class)->findBy(['partner_id' => $update->getMessage()->getFrom()->getId(), 'is_active' => true]));

        $elements = $this->em->getRepository(Element::class)->findBy(['group_id' => $groupIds]);

        $keyboard = [];
        /** @var Element $element */
        foreach ($elements as $element) {
            $callbackData = implode(':', ['group', 'settings', 'get', $element->getGroupId()]);
            $keyboard[] = [InlineKeyboardButton::withCallbackData(ucfirst($element->getName()), $callbackData), InlineKeyboardButton::withCallbackData('⚙️ Настройки', $callbackData)];
        }

        if ($keyboard) {
            $markup = new InlineKeyboardMarkup($keyboard);
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Вот список ваших групп: (всего ' . count($elements) . ' штук).'
            );
            $message->setReplyMarkup($markup);
        } else {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'У вас еще нет групп.'
            );
        }


        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        return parent::isSupported($update) && $update->getMessage()->getText() === StartPipe::GROUPS_KEY;
    }
}