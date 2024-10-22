<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BlockAllChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BlockAllGlobalChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BlockChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\CharsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\DailyMessagesCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\ForwardChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\HoldTimeChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\LinkChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\ReferralsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\SleepChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\StopWordChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\WordsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\CharsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\DailyMessageCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\HoldTimeException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\ReferralsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\RepostException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\SleepException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\StopWordException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\WordsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\DailyMessageLogger;
use App\Botonarioum\Bots\Helpers\IsChatAdministrator;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorSetting;
use App\Events\SpamDetectedEvent;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GroupMessagePipe extends BaseMessagePipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ReferralsCountChecker
     */
    private $referralsCountChecker;
    /**
     * @var WordsCountChecker
     */
    private $wordsCountChecker;
    /**
     * @var CharsCountChecker
     */
    private $charsCountChecker;
    /**
     * @var LinkChecker
     */
    private $linkChecker;
    /**
     * @var DailyMessagesCountChecker
     */
    private $dailyMessageCountChecker;
    /**
     * @var HoldTimeChecker
     */
    private $holdTimeChecker;
    /**
     * @var DailyMessageLogger
     */
    private $dailyMessageLogger;
    /**
     * @var BlockChecker
     */
    private $blockChecker;
    /**
     * @var BlockAllChecker
     */
    private $blockAllChecker;
    /**
     * @var BlockAllGlobalChecker
     */
    private $blockAllGlobalChecker;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var ForwardChecker
     */
    private $repostChecker;
    /**
     * @var StopWordChecker
     */
    private $stopWordChecker;
    /**
     * @var SleepChecker
     */
    private $sleepChecker;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, RedisStorage $redisStorage, DailyMessageLogger $dailyMessageLogger, SleepChecker $sleepChecker, HoldTimeChecker $holdTimeChecker, ReferralsCountChecker $referralsCountChecker, WordsCountChecker $wordsCountChecker, CharsCountChecker $charsCountChecker, LinkChecker $linkChecker, DailyMessagesCountChecker $dailyMessagesCountChecker, BlockChecker $blockChecker, BlockAllChecker $blockAllChecker, BlockAllGlobalChecker $blockAllGlobalChecker, ForwardChecker $repostChecker, StopWordChecker $stopWordChecker)
    {
        $this->sleepChecker = $sleepChecker;
        $this->stopWordChecker = $stopWordChecker;
        $this->referralsCountChecker = $referralsCountChecker;
        $this->wordsCountChecker = $wordsCountChecker;
        $this->charsCountChecker = $charsCountChecker;
        $this->linkChecker = $linkChecker;
        $this->dailyMessageCountChecker = $dailyMessagesCountChecker;
        $this->holdTimeChecker = $holdTimeChecker;
        $this->dailyMessageLogger = $dailyMessageLogger;
        $this->blockChecker = $blockChecker;
        $this->blockAllChecker = $blockAllChecker;
        $this->blockAllGlobalChecker = $blockAllGlobalChecker;
        $this->repostChecker = $repostChecker;
        $this->em = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->client = $redisStorage->client();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $isUserAdmin = (new IsChatAdministrator($bot, $update->getMessage()->getChat()))->checkUser($update->getMessage()->getFrom());
        $isBotAdmin = (new IsChatAdministrator($bot, $update->getMessage()->getChat()))->checkBot($bot);

        // Если у бота нет админских прав - не модерируем
        if (false === $isBotAdmin) return true;

        // Если сообщение написал админ - не модерируем
        if (true === $isUserAdmin) return true;

        $groupId = $update->getMessage()->getChat()->getId();

        /** @var ModeratorSetting $setting */
        $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup($groupId);

        try {
            $this->sleepChecker->check($update, $setting);
            $this->stopWordChecker->check($update, $setting);
            $this->repostChecker->check($update, $setting);
            $this->linkChecker->check($update, $setting);
            $this->blockChecker->check($update, $setting);
            $this->blockAllChecker->check($update, $setting);
            $this->blockAllGlobalChecker->check($update, $setting);
            $this->dailyMessageCountChecker->check($update, $setting);
            $this->wordsCountChecker->check($update, $setting);
            $this->charsCountChecker->check($update, $setting);
            $this->referralsCountChecker->check($update, $setting);
            $this->holdTimeChecker->check($update, $setting);

            $this->dailyMessageLogger->set($update);

            return true;
        } catch (SleepException $sleepException) {
            $errorMessage = 'Действует спящий режим.' . PHP_EOL;
            $errorMessage .= 'Чат закрыт с ' . $setting->getSleepFrom() . ' до ' . $setting->getSleepUntil() . PHP_EOL;

            $errorMessage .= PHP_EOL;

            $errorMessage .= 'There is a sleep mode.' . PHP_EOL;
            $errorMessage .= 'Chat is closed from ' . $setting->getSleepFrom() . ' to ' . $setting->getSleepUntil() . PHP_EOL;

        } catch (RepostException $repostException) {
            $errorMessage = 'Перепост сообщений запрещен.';
        } catch (CharsCountException $charsCountException) {
            $errorMessage = 'Максимальное количество символов: ' . $setting->getMaxMessageCharsCount();
        } catch (WordsCountException $wordsCountException) {
            $errorMessage = 'Максимальное количество слов: ' . $setting->getMaxMessageWordsCount();
        } catch (LinkException $linkException) {
            $errorMessage = 'Ссылки запрещенны';
        } catch (ReferralsCountException $referralsCountException) {
            $errorMessage = $referralsCountException->getMessage();
        } catch (DailyMessageCountException $dailyMessageCountException) {
            $errorMessage = 'Превышено максимально количество сообщений в сутки';
        } catch (HoldTimeException $holdTimeException) {
            $errorMessage = 'Вы недавно подключились в группу. Скоро сможете опубликовать пост. Период молчания не закончился. Подождите.';
        } catch (BanException $banException) {
            $errorMessage = 'Пользователь забанен админом.';
        } catch (StopWordException $stopWordException) {
            $errorMessage = 'Объявление удалено.' . PHP_EOL;
            $errorMessage .= 'Тема и текст не соответствует правилам чата.' . PHP_EOL;

            $errorMessage .= PHP_EOL;

            $errorMessage .= 'Post removed.' . PHP_EOL;
            $errorMessage .= 'This text falls short of to the rules.' . PHP_EOL;

//            $errorMessage = 'Вы использовали запрещенные слова, поэтому объявление удалено.';
        } catch (\Exception $exception) {
            $errorMessage = 'Что-то пошло не так :(';
        }

        $this->dispatcher->dispatch(SpamDetectedEvent::EVENT_NAME, new SpamDetectedEvent($update, $bot));

        $tempMessage = $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $errorMessage
        ));
        $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId()));

        $this->client->lpush(RedisKeys::makeTempMessageKey(), [json_encode(['chat_id' => $update->getMessage()->getChat()->getId(), 'message_id' => $tempMessage->getMessageId(), 'created' => time(), 'token' => $bot->getToken()])]);
        $this->client->expire(RedisKeys::makeTempMessageKey(), 60 * 60 * 24);


        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (null === $update->getMessage()) return false;

        return $update->getMessage()->getChat()->getId() < 0;
    }
}