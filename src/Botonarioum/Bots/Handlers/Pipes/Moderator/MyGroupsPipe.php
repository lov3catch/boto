<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Entity\Element;
use App\Entity\ModeratorGroupOwners;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

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