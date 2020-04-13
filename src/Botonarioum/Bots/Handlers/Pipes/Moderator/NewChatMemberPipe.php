<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\BotChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\JoinToChatLogger;
use App\Botonarioum\Bots\Helpers\BuildKeyboard;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorSetting;
use App\Events\AddedUserInGroupEvent;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\ChatMember;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\FileId;
use Formapro\TelegramBot\Message;
use Formapro\TelegramBot\SendDocument;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewChatMemberPipe extends AbstractPipe
{
    private const HTML_PARSE_MODE = 'HTML',
        MARKDOWN_PARSE_MODE = 'Markdown',
        MARKDOWN_V2_PARSE_MODE = 'MarkdownV2';

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var JoinToChatLogger
     */
    private $joinChannelLogger;
    /**
     * @var BotChecker
     */
    private $botChecker;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Client
     */
    private $client;

    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $entityManager, BotChecker $botChecker, JoinToChatLogger $joinToChatLogger, RedisStorage $redisStorage)
    {
        $this->dispatcher = $dispatcher;
        $this->joinChannelLogger = $joinToChatLogger;
        $this->botChecker = $botChecker;
        $this->em = $entityManager;
        $this->client = $redisStorage->client();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $this->dispatcher->dispatch(AddedUserInGroupEvent::EVENT_NAME, new AddedUserInGroupEvent($update, $bot));

        if ($update->getMessage()->getNewChatMember()->isBot()) return true;

        $groupId = $update->getMessage()->getChat()->getId();

        $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup($groupId);


        $this->removeStandardGreeting($bot, $update);

//        $this->removeLastGreeting($bot, $update);

        $this->addNewGreeting($bot, $update, $setting);
        $this->addNewGreetingMedia($bot, $update, $setting);

        // todo: логировать JoinToChatLogger
        // todo: тут баг в том, что мы трекаем подключение пользователя в группу, но только одного, а их может быть несколько
        $this->joinChannelLogger->set($update);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (!$update->getMessage() instanceof Message) return false;

        return ($update->getMessage()->getNewChatMember() instanceof ChatMember);
    }


    private function removeLastGreeting(Bot $bot, Update $update): void
    {
        $elements = array_unique($this->client->lrange(RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()), 0, -1));

        $this->client->del([RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId())]);

        foreach ($elements as $element) {
            try {
                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;

                $lastGreetingIdKey = RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId());
//                if ($this->client->exists($lastGreetingIdKey)) {
                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$element));
//                }

            } catch (\Exception $exception) {
                var_dump($exception);
                $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey(1), $element);
            }
        }
    }


//        try {
//            $lastGreetingIdKey = RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId());
//            var_dump('GREETING ID TO DELETE: ' . $lastGreetingIdKey);
//
//                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$element));
//            } catch (\Exception $exception) {
//                $this->client->lpush(RedisKeys::makeLastGreetingsMessageQueueIdKey($update->getMessage()->getChat()->getId()), $element);
//            }
//        }
//
//
////        try {
////            $lastGreetingIdKey = RedisKeys::makeLastGreetingsMessageQueueIdKey();
////            var_dump('GREETING ID TO DELETE: ' . $lastGreetingIdKey);
////
//////            $lastGreetingIdKey = implode(':', ['moderator', 'last_greeting', $update->getMessage()->getChat()->getId()]);
////            if ($this->client->exists($lastGreetingIdKey)) {
////                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$this->client->get($lastGreetingIdKey)));
////            } else {
////                var_dump('GREETING ID TO DELETE: ' . $lastGreetingIdKey . ' NOT FOUND!');
////            }
////        } catch (\Exception $exception) {
////            var_dump('GREETING EXCEPTION: ' . $exception->getMessage());
////        }
//    }

    private function addNewGreeting(Bot $bot, Update $update, ModeratorSetting $setting): void
    {
        $greeting = $setting->getGreetingMessage();
        $greeting = str_replace('{username}', $update->getMessage()->getNewChatMember()->getFirstName(), $greeting);
        $greeting = str_replace('{chat_title}', $update->getMessage()->getChat()->getTitle(), $greeting);

        $msg = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $greeting
        );
        $msg->setParseMode(self::HTML_PARSE_MODE);

        if ($setting->getGreetingButtons()) {
            $msg->setReplyMarkup((new BuildKeyboard())->build($setting->getGreetingButtons()));

        }

        $newGreetingMessage = $bot->sendMessage($msg);

        $this->trackMessageIdInRedis(
            RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()),
            $bot->getToken(),
            $update->getMessage()->getChat()->getId(),
            $newGreetingMessage->getMessageId()
        );
    }

    private function trackMessageIdInRedis(string $key, string $token, int $chatId, int $messageId): void
    {
        $this->client->lpush($key, json_encode([
            'token'      => $token,
            'chat_id'    => $chatId,
            'message_id' => $messageId
        ]));
    }

    private function addNewGreetingMedia(Bot $bot, Update $update, ModeratorSetting $setting): void
    {
        if (null === $setting->getGreetingFiles()) return;

        try {
            $updateData = $setting->getGreetingFiles();

            if (isset($updateData['message']['video'])) {
                $message = 'VIDEO';
                $fileId = $updateData['message']['video']['file_id'];
            }

            if (isset($updateData['message']['audio'])) {
                $message = 'AUDIO';
                $fileId = $updateData['message']['audio']['file_id'];
            }

            if (isset($updateData['message']['animation'])) {
                $message = 'ANIMATION';
                $fileId = $updateData['message']['animation']['file_id'];
            }

            if (isset($updateData['message']['photo'])) {
                $message = 'PHOTO';
                $photoInstances = $updateData['message']['photo'];
                $photo = array_pop($photoInstances);
                $fileId = $photo['file_id'];
            }

            if (isset($updateData['message']['document'])) {
                $message = 'DOCUMENT';
                $fileId = $updateData['message']['document']['file_id'];
            }

            $newGreetingMediaMessage = $bot->sendDocument(SendDocument::withFileId($update->getMessage()->getChat()->getId(), new FileId($fileId)));

            $this->trackMessageIdInRedis(
                RedisKeys::makeLastGreetingMediasMessageIdKey($update->getMessage()->getChat()->getId()),
                $bot->getToken(),
                $update->getMessage()->getChat()->getId(),
                $newGreetingMediaMessage->getMessageId()
            );
        } catch (\Throwable $exception) {
            //
        }


    }

    private function removeStandardGreeting(Bot $bot, Update $update): void
    {
        try {
            $deleteMessage = new DeleteMessage(
                $update->getMessage()->getChat()->getId(),
                $update->getMessage()->getMessageId());

            $bot->deleteMessage($deleteMessage);
        } catch (\Exception $exception) {
            //
        }
    }
}