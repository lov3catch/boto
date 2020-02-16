<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\ModeratorSetting;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class SettingsGetterPipe extends CallbackPipe
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
        $groupId = (int)explode(':', $update->getCallbackQuery()->getData())[4];

        /** @var ModeratorSetting $setting */
        $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup($groupId);

        $selectedSetting = explode(':', $update->getCallbackQuery()->getData())[3];

        if ('max_daily_messages_count' === $selectedSetting) {
            $message = 'Максимальное количество сообщений в день от определенного пользователя. Текущее значение: ' . $setting->getMaxDailyMessageCount() . '.';
        }

        if ('min_referrals_count' === $selectedSetting) {
            $message = 'Минимальное количество рефералов, которых должен пригласить пользователь группы, что бы иметь возможность оставлять сообщения. Текущее значение: ' . $setting->getMinReferralsCount() . '.';
        }

        if ('holdtime' === $selectedSetting) {
            $message = 'Новички смогут оставлять соодщение спустя указаный период (в секундах). Текущее значение: ' . $setting->getHoldtime() . '.';
        }

        if ('max_chars_count' === $selectedSetting) {
            $message = 'Максимально количество символов в сообщении. Текущее значение: ' . $setting->getMaxMessageCharsCount() . '.';
        }

        if ('max_words_count' === $selectedSetting) {
            $message = 'Максимальное количество слов в сообщении. Текущее значение: ' . $setting->getMaxMessageWordsCount() . '.';
        }

        if ('greeting' === $selectedSetting) {
            $message = 'Изменить приветсвие для новый участников группы. Текущее значение: ' . $setting->getGreetingMessage() . '.';
        }

        if ('greeting_buttons' === $selectedSetting) {
            $message = 'Изменить клавиатуру под приветствием.';
        }

        if ('is_link_enable' === $selectedSetting) {
            $groupId = explode(':', $update->getCallbackQuery()->getData())[4];

            $linkEnableCallback = implode(':', ['moderator', 'group', 'settings', 'link', 'enable', $groupId]);
            $linkDisableCallback = implode(':', ['moderator', 'group', 'settings', 'link', 'disable', $groupId]);

            $message = 'Разрешить/запретить пользователям группы использовать ссылки в своих сообщениях?';
            $sendMessage = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), $message);
            $sendMessage->setReplyMarkup(new InlineKeyboardMarkup([[InlineKeyboardButton::withCallbackData('Разрешить', $linkEnableCallback), InlineKeyboardButton::withCallbackData('Запретить', $linkDisableCallback)]]));
            $bot->sendMessage($sendMessage);

            return true;
        }

        if ($message ?? false) {
            $callbackData = str_replace(':set:', ':change:', $update->getCallbackQuery()->getData());
            $sendMessage = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), $message);
            $sendMessage->setReplyMarkup(new InlineKeyboardMarkup([[InlineKeyboardButton::withCallbackData('Изменить', $callbackData)]]));
        } else {
            $sendMessage = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), 'Данная настройка находится в разработке. Ожидайте :)');
        }

        $bot->sendMessage($sendMessage);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'settings', 'set']))) return false;

        return true;
    }
}