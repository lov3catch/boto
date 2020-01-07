<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

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
        $groups = $this->em->getRepository(ModeratorGroupOwners::class)->findBy(['partner_id' => $update->getMessage()->getFrom()->getId()]);

        $keyboard = [];
        /** @var ModeratorGroupOwners $group */
        foreach ($groups as $group) {
            $keyboard[] = InlineKeyboardButton::withCallbackData('ID: ' . $group->getGroupId(), 'callback-data');
        }

        $markup = new InlineKeyboardMarkup([$keyboard]);

        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Количество групп: ' . count($groups) . '.'
        );
        $message->setReplyMarkup($markup);

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        return parent::isSupported($update) && $update->getMessage()->getText() === StartPipe::GROUPS_KEY;
    }
}