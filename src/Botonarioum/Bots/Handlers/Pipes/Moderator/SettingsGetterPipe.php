<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Entity\ModeratorSetting;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
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
//        var_dump($update->getCallbackQuery()->getData());die;
        $groupId = (int)explode(':', $update->getCallbackQuery()->getData())[4];

        /** @var ModeratorSetting $settings */
        $setting = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
                       ->where('setting.is_default = :isd')
                       ->orWhere('setting.group_id = :grid')
                       ->orderBy('setting.is_default', 'ASC')
                       ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
                       ->getQuery()
                       ->getResult()[0];

        $selectedSetting = explode(':', $update->getCallbackQuery()->getData())[3];

        if ('max_daily_messages_count' === $selectedSetting) {
            $message = 'Максимальное количество сообщений в день от определенного пользователя. Текущее значение: ' . $setting->getMaxDailyMessagesCount() . '.';
//            $callbackData = str_replace('set', 'change', $update->getCallbackQuery()->getData());
        }

        if ('min_referrals_count' === $selectedSetting) {
            $message = 'Минимальное количество рефералов, которых должен пригласить пользователь группы, что бы иметь возможность оставлять сообщения. Текущее значение: ' . $setting->getMinReferralsCount() . '.';
//            $callbackData = '12';
        }

        if ('holdtime' === $selectedSetting) {
            $message = 'Новички смогут оставлять соодщение спустя указаный период (в секундах). Текущее значение: ' . $setting->getHoldtime() . '.';
//            $callbackData = '13';
        }

        if ('max_chars_count' === $selectedSetting) {
            $message = 'Максимально количество символов в сообщении. Текущее значение: ' . $setting->getMaxCharsCount() . '.';
//            $callbackData = '14';
        }

        if ('max_words_count' === $selectedSetting) {
            $message = 'Максимальное количество слов в сообщении. Текущее значение: ' . $setting->getMaxWordsCount() . '.';
//            $callbackData = '15';
        }

        if ('greeting' === $selectedSetting) {
            $message = 'Изменить приветсвие для новый участников группы. Текущее значение: ' . $setting->getGreeting() . '.';
        }

        if ('greeting_buttons' === $selectedSetting) {
            $message = 'Изменить клавиатуру под приветствием.';
        }


        if ('is_link_enable' === $selectedSetting) {
//            "group:settings:set:is_link_enable:-1001208545789"
            $groupId = explode(':', $update->getCallbackQuery()->getData())[4];
//            var_dump($update->getCallbackQuery()->getData());die;

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

//        $message = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), $message ?? );
//        if ($callbackData ?? false) {
//            $message->setReplyMarkup(new InlineKeyboardMarkup([[InlineKeyboardButton::withCallbackData('Изменить', $callbackData)]]));
//        }
//        $bot->sendMessage($message);

        return true;

        return parent::processing($bot, $update); // TODO: Change the autogenerated stub
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'settings', 'set']))) return false;

        return true;
    }
}