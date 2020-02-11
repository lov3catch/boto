<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\Element;
use App\Entity\ModeratorGroupOwners;
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
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $groupIds = array_map(function (ModeratorGroupOwners $groupOwners) {
            return $groupOwners->getGroupId();
        }, $this->em->getRepository(ModeratorGroupOwners::class)->findBy(['partner_id' => $update->getCallbackQuery()->getFrom()->getId()]));

        $elements = $this->em->getRepository(Element::class)->findBy(['group_id' => $groupIds]);

        $keyboard = [];
        /** @var Element $element */
        foreach ($elements as $element) {
            $callbackData = implode(':', ['group', 'settings', 'get', $element->getGroupId()]);
            $keyboard[] = [InlineKeyboardButton::withCallbackData(ucfirst($element->getName()), $callbackData), InlineKeyboardButton::withCallbackData('⚙️ Настройки', $callbackData)];
        }

        $markup = new InlineKeyboardMarkup($keyboard);

        $message = new SendMessage(
            $update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'Вот список ваших групп: (всего ' . count($elements) . ' штук).'
        );
        $message->setReplyMarkup($markup);

        $bot->sendMessage($message);


        $target = implode(':', ['moderator', 'group', 'settings', 'await', $update->getCallbackQuery()->getFrom()->getId()]);

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

        return (bool)explode(':', $this->redisStorage->client()->get($target));
    }
}