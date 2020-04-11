<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Helpers\BuildKeyboard;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorSetting;
use App\Storages\RedisStorage;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use function Formapro\Values\get_values;

class SettingsAwaitPipe extends MessagePipe
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

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        if (!$update->getMessage()) return false;

        if ((bool)$update->getCallbackQuery()) {
            $fromId = $update->getCallbackQuery()->getFrom()->getId();
            $chatId = $update->getCallbackQuery()->getMessage()->getChat()->getId();
        } else {
            $fromId = $update->getMessage()->getFrom()->getId();
            $chatId = $update->getMessage()->getChat()->getId();
        }

        $target = implode(':', ['moderator', 'group', 'settings', 'await', $fromId]);

        $result = $this->redisStorage->client()->get($target);

        if (is_string($result)) return (bool)explode(':', $this->redisStorage->client()->get($target));

        return false;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        if ((bool)$update->getCallbackQuery()) {
            $fromId = $update->getCallbackQuery()->getFrom()->getId();
            $chatId = $update->getCallbackQuery()->getMessage()->getChat()->getId();
        } else {
            $fromId = $update->getMessage()->getFrom()->getId();
            $chatId = $update->getMessage()->getChat()->getId();
        }

        $target = RedisKeys::makeAwaitSettingChangeKey($fromId);


        [$groupId, $selectedSetting] = explode(':', $this->redisStorage->client()->get($target));

        $message = 'Настройки изменены. Новое значение: ' . $update->getMessage()->getText();

        try {
            if ($selectedSetting === 'greeting' || $selectedSetting === 'stop_words') {
                Assertion::string($update->getMessage()->getText(), 'Должна быть строка');
                Assertion::notBlank($update->getMessage()->getText(), 'Строка не может быть пустой');
            } else if ($selectedSetting === 'sleep_mode') {
                Assertion::string($update->getMessage()->getText(), 'Должна быть строка');
                Assertion::notBlank($update->getMessage()->getText(), 'Строка не может быть пустой');

                if (strtolower(trim($update->getMessage()->getText())) !== 'off') {
                    $pattern = "/^\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*-\s*(2[0-3]|[01]?[0-9]):([0-5]?[0-9])\s*$/";

                    Assertion::regex($update->getMessage()->getText(), $pattern, 'Не верный формат вемени. Пример: 14:00 - 23:30, или например 22:00 - 06:25');
                }
            } else if ($selectedSetting === 'greeting_buttons') {
                $markup = (new BuildKeyboard())->build($update->getMessage()->getText());
                $msg = new SendMessage($chatId, 'Так будут выглядить кнопки под приветствием:');
                $msg->setReplyMarkup($markup);
                $bot->sendMessage($msg);

            } else if ($selectedSetting === 'greeting_files') {
                $message = 'Настройки изменены.';
            } else {
                Assertion::digit($update->getMessage()->getText(), 'Число от 0 до 9999');
            }


            /** @var ModeratorSetting $setting */
            $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup((int)$groupId);

            /** @var ModeratorSetting $newSettings */
            if ($setting->getIsDefault()) {
                $setting = clone $setting;
            }

            $this->changeSettings($setting, $selectedSetting, $update->getMessage()->getText(), $update);
            $setting->setGroupId((int)$groupId);
            $setting->setIsDefault(false);

            $this->em->persist($setting);
            $this->em->flush();

            $this->redisStorage->client()->del([$target]);

        } catch (AssertionFailedException $exception) {
            $message = $exception->getMessage();
        } catch (\Exception $exception) {
            $message = 'Что-то пошло не так :(';
        }

        $bot->sendMessage(new SendMessage(
            $chatId,
            $message
        ));

        return true;
    }

    private function changeSettings(ModeratorSetting $setting, string $selectedSetting, $value, Update $update): ModeratorSetting
    {
        if ('greeting_files' === $selectedSetting) {
            if ($update->getMessage()->getText() && 'off' === strtolower(trim($value))) {
                $setting->setGreetingFiles(null);
            } else {
                $setting->setGreetingFiles(get_values($update));
            }
        }

        if ('sleep_mode' === $selectedSetting) {
            if ('off' === strtolower(trim($value))) {
                $setting->resetSleepMode();
            } else {
                [$from, $until] = $input = array_map(function (string $inp) {

                    return trim($inp);
                }, explode('-', trim($value)));
                $setting->setSleepFrom($from);
                $setting->setSleepUntil($until);
            }
        }

        if ('stop_words' === $selectedSetting) {
            $setting->setStopWords(array_map(function (string $str) {
                return trim($str);
            }, explode(',', $value)));
        }

        if ('greeting_buttons' === $selectedSetting) {
            $setting->setGreetingButtons($value);
        }

        if ('max_daily_messages_count' === $selectedSetting) {
            $setting->setMaxDailyMessageCount((int)$value);
        }

        if ('min_referrals_count' === $selectedSetting) {
            $setting->setMinReferralsCount((int)$value);
        }

        if ('holdtime' === $selectedSetting) {
            $setting->setHoldtime((int)$value);
        }

        if ('max_chars_count' === $selectedSetting) {
            $setting->setMaxMessageCharsCount((int)$value);
        }

        if ('max_words_count' === $selectedSetting) {
            $setting->setMaxMessageWordsCount((int)$value);
        }

        if ('greeting' === $selectedSetting) {
            $setting->setGreetingMessage($value);
        }

        return $setting;
    }
}