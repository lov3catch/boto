<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class SettingsChangerPipe extends CallbackPipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RedisStorage
     */
    private $redisStorage;

    public function __construct(EntityManagerInterface $entityManager, RedisStorage $redisStorage)
    {
        $this->em = $entityManager;
        $this->redisStorage = $redisStorage;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $selectedSetting = explode(':', $update->getCallbackQuery()->getData())[3];

        if ('max_daily_messages_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить максимальное количество сообщений в день от определенного пользователя. Допустимые значения: от 0 до 9999.';
        }

        if ('min_referrals_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить минимальное количество рефералов, которых должен пригласить пользователь группы, что бы иметь возможность оставлять сообщения. Допустимое значение: от 0 до 9999.';
        }

        if ('holdtime' === $selectedSetting) {
            $message = 'Вы собираетесь изменить через сколько новички смогут оставлять соодщение спустя указаный период (в секундах). Допустимое значение: от 0 до 9999.';
        }

        if ('max_chars_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменитить максимально количество символов в сообщении. Допустимое значение: от 0 до 9999.';
        }

        if ('max_words_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить максимальное количество слов в сообщении. Допустимое значение: от 0 до 9999.';
        }

        if ('greeting' === $selectedSetting) {
            $message = 'Изменить приветсвие для новый участников группы. Ключи: {username} - ник пользователя, {chat_title} - название группы';
        }

        if ('greeting_buttons' === $selectedSetting) {
            $message = 'Изменить кнопки под приветствием. Пример: (Моя ссылка 1: http://example1.com)';
        }

        if ($message ?? false) {
            $sendMessage = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), $message);

            $groupId = array_reverse(explode(':', $update->getCallbackQuery()->getData()))[0];
            $callbackData = implode(':', ['group', 'settings', 'cancel', $groupId]);

            $sendMessage->setReplyMarkup(new InlineKeyboardMarkup([[InlineKeyboardButton::withCallbackData('Я передумал менять настройки', $callbackData)]]));

            $target = RedisKeys::makeAwaitSettingChangeKey($update->getCallbackQuery()->getFrom()->getId());              // группа
            $groupIdAndSettingName = implode(':', [$groupId, $selectedSetting]);                                                                    // настройка

            $this->redisStorage->client()->set($target, $groupIdAndSettingName);
            $this->redisStorage->client()->expire($target, 60 * 60 * 24 * 7);

        } else {
            $sendMessage = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), 'Данная настройка находится в разработке. Ожидайте :)');
        }

        $bot->sendMessage($sendMessage);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'settings', 'change']))) return false;

        return true;
    }
}