<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorGroup;
use App\Entity\ModeratorGroupOwners;
use App\Entity\ModeratorOwner;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class SettingsCancelPipe extends CallbackPipe
{
    /**
     * @var RedisStorage
     */
    private $redisStorage;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(RedisStorage $redisStorage, EntityManagerInterface $entityManager)
    {
        $this->redisStorage = $redisStorage;
        $this->em = $entityManager;
//        $this->myGroupsPipe = $myGroupsPipe;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $groupOwnersRepository = $this->em->getRepository(ModeratorOwner::class);

//        var_dump($update->getCallbackQuery()->getMessage()->getFrom());die;

        $myGroups = $groupOwnersRepository->findBy(['user_id' => $update->getCallbackQuery()->getFrom()->getId()]);


//        $update->getCallbackQuery()->getMessage()

        if ([] === $myGroups) {
            $bot->sendMessage(new SendMessage(
                $update->getCallbackQuery()->getMessage()->getChat()->getId(),
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
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'Вот список ваших групп: (всего ' . count($myGroupsInfo) . ' штук).'
        );
        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

//        return true;
//        $groupIds = array_map(function (ModeratorGroupOwners $groupOwners) {
//            return $groupOwners->getGroupId();
//        }, $this->em->getRepository(ModeratorGroupOwners::class)->findBy(['partner_id' => $update->getCallbackQuery()->getFrom()->getId()]));
//
//        $elements = $this->em->getRepository(Element::class)->findBy(['group_id' => $groupIds]);
//
//        $keyboard = [];
//        /** @var Element $element */
//        foreach ($elements as $element) {
//            $callbackData = implode(':', ['group', 'settings', 'get', $element->getGroupId()]);
//            $keyboard[] = [InlineKeyboardButton::withCallbackData(ucfirst($element->getName()), $callbackData), InlineKeyboardButton::withCallbackData('⚙️ Настройки', $callbackData)];
//        }
//
//        $markup = new InlineKeyboardMarkup($keyboard);
//
//        $message = new SendMessage(
//            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
//            'Вот список ваших групп: (всего ' . count($elements) . ' штук).'
//        );
//        $message->setReplyMarkup($markup);
//
//        $bot->sendMessage($message);

//        $this->myGroupsPipe->processing($bot, $update);

        $target = RedisKeys::makeAwaitSettingChangeKey($update->getCallbackQuery()->getFrom()->getId());

        $this->redisStorage->client()->del([$target]);

        return true;
    }


    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        $target = implode(':', ['moderator', 'group', 'settings', 'await', $update->getCallbackQuery()->getFrom()->getId()]);

        $value = $this->redisStorage->client()->get($target);

        if ((bool)$value) return (bool)explode(':', $this->redisStorage->client()->get($target));

        return false;
    }
}