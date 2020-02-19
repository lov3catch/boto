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
//        if (!$update->getCallbackQuery()) return false;

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

        return (bool)explode(':', $this->redisStorage->client()->get($target));


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
/////
//
//        try {
//
//            $markup = (new BuildKeyboard())->build($update->getMessage()->getText());
//            $msg = new SendMessage($chatId, '$text');
//            $msg->setReplyMarkup($markup);
//            $bot->sendMessage($msg);
//            var_dump($update->getMessage()->getText());
//            die;
//        } catch (\Exception $exception) {
//            $bot->sendMessage(new SendMessage($chatId, 'Не верный формат записи'));
//        }
        ////////

        try {
            if ($selectedSetting === 'greeting') {
                Assertion::string($update->getMessage()->getText(), 'Должна быть строка');
                Assertion::notBlank($update->getMessage()->getText(), 'Строка не может быть пустой');
            } else if ($selectedSetting === 'greeting_buttons') {
//                Assertion::digit($update->getMessage()->getText(), 'Число от 0 до 9999');
//                (new BuildKeyboard())->build($update->getMessage()->getText());


                $markup = (new BuildKeyboard())->build($update->getMessage()->getText());
                $msg = new SendMessage($chatId, 'Так будут выглядить кнопки под приветствием:');
                $msg->setReplyMarkup($markup);
                $bot->sendMessage($msg);

            } else {
                // greeting

                Assertion::digit($update->getMessage()->getText(), 'Число от 0 до 9999');
            }


            $message = 'Настройки изменены. Новое значение: ' . $update->getMessage()->getText();

            /** @var ModeratorSetting $setting */
            $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup((int)$groupId);
//            $setting = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
//                           ->where('setting.is_default = :isd')
//                           ->orWhere('setting.group_id = :grid')
//                           ->orderBy('setting.is_default', 'ASC')
//                           ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
//                           ->getQuery()
//                           ->getResult()[0];


            /** @var ModeratorSetting $newSettings */
            if ($setting->getIsDefault()) {
                $setting = clone $setting;
            }

            $this->changeSettings($setting, $selectedSetting, $update->getMessage()->getText());
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

    private function changeSettings(ModeratorSetting $setting, string $selectedSetting, $value): ModeratorSetting
    {

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