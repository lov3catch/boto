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

        if ('greeting_files' === $selectedSetting) {
            $message = 'Добавление медиа-файлов под приветствием пользователей.';
            $message .= PHP_EOL;
            $message .= 'Поддерживаемые форматы: .gif, .pdf, .mp3, .zip, .txt';
            $message .= PHP_EOL . PHP_EOL;
            $message .= 'Отправьте: off - чтобы деактивировать функцию.';
        }

        if ('sleep_mode' === $selectedSetting) {
            $message = 'Вы собираетесь изменить расписание функции: «режим сна» в чате. Период ОТ и ДО, во время которого пользователи не смогут отправлять сообщения. Задайте настройки.';
            $message .= PHP_EOL . PHP_EOL;
            $message .= 'Например:' . PHP_EOL;
            $message .= '12:30 - 14:30 (спящий режим от 12:30 до 14:30)' . PHP_EOL;
            $message .= '22:00 - 08:00 (спящий режим от 22:00 до 08:00 следующего дня)';
            $message .= PHP_EOL . PHP_EOL;
            $message .= 'Отправьте: off - чтобы деактивировать функцию.';
        }

        if ('stop_words' === $selectedSetting) {
            $message = 'Вы собираетесь изменить список СТОП-слов. Сообщение, в котором будет обнаружено хоть одно слово из этого списка - будет заблокировано. Отправьте список слов через запятую, например: казино, негодяй, кукушка';
        }

        if ('max_daily_messages_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить максимальное количество сообщений в день от определенного пользователя. Допустимые значения: от 0 до 9999.';
        }

        if ('min_referrals_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить минимальное количество рефералов, которых должен пригласить пользователь группы, что бы иметь возможность оставлять сообщения. Допустимое значение: от 0 до 999999.';
        }

        if ('holdtime' === $selectedSetting) {
            $message = 'Вы собираетесь изменить через сколько новички смогут оставлять соодщение спустя указаный период (в секундах). Допустимое значение: от 0 до 999999.';
        }

        if ('max_chars_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменитить максимально количество символов в сообщении. Допустимое значение: от 0 до 999999.';
        }

        if ('max_words_count' === $selectedSetting) {
            $message = 'Вы собираетесь изменить максимальное количество слов в сообщении. Допустимое значение: от 0 до 999999.';
        }

        if ('greeting' === $selectedSetting) {
            $message = 'Отправьте мне текст приветствия. Например:{username} Здравствуйте, вы в группе: {chat_title}. Внимание! {username} это Имя вступившего, а {chat_title} это ваша группа.' . PHP_EOL . PHP_EOL . 'Значит, приветствие будет такое: «Ирина Здравствуйте, вы в группе: Аренда». Далее в приветствие можно вписать любой ваш текст с ссылками.';
        }

        if ('greeting_buttons' === $selectedSetting) {
            $message = 'Вставьте своё название кнопки вместо слова «Текст 1». Затем после двоеточия вставьте вашу ссылку:' . PHP_EOL . '(Текст 1: http://example1.com)' . PHP_EOL . PHP_EOL . 'Если вам надо 2 или 3 кнопки в ряд, тогда без пробела заполняйте следующую конструкцию:' . PHP_EOL . '(Текст 1: http://example1.com)(Текст 2: http://example2.com)' . PHP_EOL . PHP_EOL . 'Следующие кнопки в столбик через нажатие Enter.';
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