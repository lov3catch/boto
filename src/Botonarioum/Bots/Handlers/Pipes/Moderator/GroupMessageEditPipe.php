<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\BlockAllChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\BlockAllGlobalChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\BlockChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\CharsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\DailyMessagesCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\HoldTimeChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\LinkChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\ReferralsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\RepostChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\EditCheckers\WordsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\BanException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\CharsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\DailyMessageCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\HoldTimeException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\ReferralsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\RepostException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\WordsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\DailyMessageLogger;
use App\Botonarioum\Bots\Helpers\IsChatAdministrator;
use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Entity\ModeratorSetting;
use App\Storages\RedisStorage;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Predis\Client;

class GroupMessageEditPipe extends BaseMessagePipe
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
     * @var RepostChecker
     */
    private $repostChecker;

    public function __construct(EntityManagerInterface $entityManager, RedisStorage $redisStorage, DailyMessageLogger $dailyMessageLogger, HoldTimeChecker $holdTimeChecker, ReferralsCountChecker $referralsCountChecker, WordsCountChecker $wordsCountChecker, CharsCountChecker $charsCountChecker, LinkChecker $linkChecker, DailyMessagesCountChecker $dailyMessagesCountChecker, BlockChecker $blockChecker, BlockAllChecker $blockAllChecker, BlockAllGlobalChecker $blockAllGlobalChecker, RepostChecker $repostChecker)
    {
        $this->referralsCountChecker = $referralsCountChecker;
        $this->wordsCountChecker = $wordsCountChecker;
        $this->charsCountChecker = $charsCountChecker;
        $this->linkChecker = $linkChecker;
//        $this->dailyMessageCountChecker = $dailyMessagesCountChecker;
        $this->holdTimeChecker = $holdTimeChecker;
//        $this->dailyMessageLogger = $dailyMessageLogger;
        $this->blockChecker = $blockChecker;
        $this->blockAllChecker = $blockAllChecker;
        $this->blockAllGlobalChecker = $blockAllGlobalChecker;
        $this->repostChecker = $repostChecker;
        $this->em = $entityManager;
        $this->client = $redisStorage->client();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $isUserAdmin = (new IsChatAdministrator($bot, $update->getEditedMessage()->getChat()))->checkUser($update->getEditedMessage()->getFrom());
        $isBotAdmin = (new IsChatAdministrator($bot, $update->getEditedMessage()->getChat()))->checkBot($bot);

        // Если у бота нет админских прав - не модерируем
        if (false === $isBotAdmin) return true;

        // Если сообщение написал админ - не модерируем
        if (true === $isUserAdmin) return true;


//        var_dump($isBotAdmin);
//        die;

//        // todo: переход на репозиторий @makasim
//        $bot->sendMessage(new SendMessage(
//            $update->getEditedMessage()->getChat()->getId(),
//            'Logging'
//        ));

        $groupId = $update->getEditedMessage()->getChat()->getId();

        /** @var ModeratorSetting $setting */
        $setting = $this->em->getRepository(ModeratorSetting::class)->getForSelectedGroup($groupId);

        /** @var ModeratorSetting $settings */
//        $setting = ($this->em->getRepository(ModeratorSetting::class)->createQueryBuilder('setting'))
//                       ->where('setting.is_default = :isd')
//                       ->orWhere('setting.group_id = :grid')
//                       ->orderBy('setting.is_default', 'ASC')
//                       ->setParameters(new ArrayCollection([new Parameter('isd', true), new Parameter('grid', (int)$groupId)]))
//                       ->getQuery()
//                       ->getResult()[0];

//        /** @var ModeratorSetting $setting */
//        $setting = $this->em->getRepository(ModeratorSetting::class)->findOneBy([]);

        try {


            $this->repostChecker->check($update, $setting);
            $this->linkChecker->check($update, $setting);
            $this->blockChecker->check($update, $setting);
            $this->blockAllChecker->check($update, $setting);
            $this->blockAllGlobalChecker->check($update, $setting);
//            $this->dailyMessageCountChecker->check($update, $setting);
            $this->wordsCountChecker->check($update, $setting);
            $this->charsCountChecker->check($update, $setting);
//            $this->referralsCountChecker->check($update, $setting);
            $this->holdTimeChecker->check($update, $setting);

//            $this->dailyMessageLogger->set($update);

//            var_dump($this->dailyMessageLogger->get($update));die;

//            $bot->sendMessage(new SendMessage(
//                $update->getEditedMessage()->getChat()->getId(),
//                'OK'
//            ));


//            (new DailyMessageLogger($this->))->set($update);      // логируем сообщение
//            (new JoinToChatLogger($this->client))->set($update);        // todo: если группа старая - надо как-то создать запись о holdtime

            return true;
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
//            $errorMessage = 'Пригласите больше людей в группу';
        } catch (DailyMessageCountException $dailyMessageCountException) {
            $errorMessage = 'Превышено максимально количество сообщений в сутки';
        } catch (HoldTimeException $holdTimeException) {
            $errorMessage = 'Вы недавно подключились в группу. Скоро сможете опубликовать пост. Период молчания не закончился. Подождите.';
        } catch (BanException $banException) {
            $errorMessage = 'Пользователь забанен админом.';
        } catch (\Exception $exception) {
            $errorMessage = 'Что-то пошло не так :(';
//            $errorMessage = $exception->getMessage();
        }

        $tempMessage = $bot->sendMessage(new SendMessage(
            $update->getEditedMessage()->getChat()->getId(),
            $errorMessage
        ));
        $bot->deleteMessage(new DeleteMessage($update->getEditedMessage()->getChat()->getId(), $update->getEditedMessage()->getMessageId()));

//        $tempMessageData = ['chat_id' => $update->getEditedMessage()->getChat()->getId(), 'message_id' => $tempMessage->getMessageId(), 'created' => time(), 'token' => $bot->getToken()];

        $this->client->lpush(RedisKeys::makeTempMessageKey(), [json_encode(['chat_id' => $update->getEditedMessage()->getChat()->getId(), 'message_id' => $tempMessage->getMessageId(), 'created' => time(), 'token' => $bot->getToken()])]);
        $this->client->expire(RedisKeys::makeTempMessageKey(), 60 * 60 * 24);


//        $this->client->set('foo', 'bar');
//        $this->client->expire('foo', 15);
        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (null === $update->getEditedMessage()) return false;

        return $update->getEditedMessage()->getChat()->getId() < 0;
    }
}