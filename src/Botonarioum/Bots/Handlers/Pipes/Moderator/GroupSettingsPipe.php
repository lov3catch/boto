<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class GroupSettingsPipe extends CallbackPipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        $settings = [
            'greeting'                 => 'Приветствие',
            'greeting_buttons'         => 'Кнопки под приветствием',
            'greeting_files'           => 'Файлы под приветствием',
            'max_daily_messages_count' => 'Максимальное к-во сообщений в день',
            'min_referrals_count'      => 'Минимальное к-во рефералов',
            'holdtime'                 => 'Заглушить новичков на (в секундах)',
            'max_chars_count'          => 'Максимальное к-во символов в сообщении',
            'max_words_count'          => 'Максимальное к-во слов в сообщении',
            'is_link_enable'           => 'Разрешить/Запретить ссылки',
            'is_forward_enable'        => 'Разрешить/Запретить перепосты',
            'stop_words'               => 'Стоп-слова | Stop-words',
            'sleep_mode'               => 'Режим сна | Sleep mode'
        ];

        $keyboard = [];
        foreach ($settings as $settingMachineName => $settingHumanName) {
            $groupId = array_reverse(explode(':', $update->getCallbackQuery()->getData()))[0];
            $callbackData = implode(':', ['group', 'settings', 'set', $settingMachineName, $groupId]);
            $keyboard[] = [InlineKeyboardButton::withCallbackData($settingHumanName, $callbackData)];
        }

        $message = new SendMessage($update->getCallbackQuery()->getMessage()->getChat()->getId(), 'Тут вы можете изменить настройки для выбранной группы. Какую из настроек желаете сменить❔');
        $message->setReplyMarkup(new InlineKeyboardMarkup($keyboard));

        $bot->sendMessage($message);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (false === strpos($update->getCallbackQuery()->getData(), implode(':', ['group', 'settings', 'get']))) return false;

        return true;
    }
}