<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\CharsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\DailyMessagesCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\HoldTimeChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\LinkChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\ReferralsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Checkers\WordsCountChecker;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\CharsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\DailyMessageCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\HoldTimeException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\LinkException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\ReferralsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\Exceptions\WordsCountException;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\RedisLogs\DailyMessageLogger;
use App\Entity\ModeratorSetting;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

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

    public function __construct(EntityManagerInterface $entityManager, DailyMessageLogger $dailyMessageLogger, HoldTimeChecker $holdTimeChecker, ReferralsCountChecker $referralsCountChecker, WordsCountChecker $wordsCountChecker, CharsCountChecker $charsCountChecker, LinkChecker $linkChecker, DailyMessagesCountChecker $dailyMessagesCountChecker)
    {
        $this->referralsCountChecker = $referralsCountChecker;
        $this->wordsCountChecker = $wordsCountChecker;
        $this->charsCountChecker = $charsCountChecker;
        $this->linkChecker = $linkChecker;
        $this->dailyMessageCountChecker = $dailyMessagesCountChecker;
        $this->holdTimeChecker = $holdTimeChecker;
        $this->dailyMessageLogger = $dailyMessageLogger;
        $this->em = $entityManager;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        // todo: переход на репозиторий @makasim
        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Logging'
        ));

        /** @var ModeratorSetting $setting */
        $setting = $this->em->getRepository(ModeratorSetting::class)->findOneBy([]);

        try {
            $this->dailyMessageCountChecker->check($update, $setting);
            $this->linkChecker->check($update, $setting);
            $this->wordsCountChecker->check($update, $setting);
            $this->charsCountChecker->check($update, $setting);
            $this->referralsCountChecker->check($update, $setting);
            $this->holdTimeChecker->check($update, $setting);
            // todo: нельзя приглашать ботов

            $this->dailyMessageLogger->set($update);

            $bot->sendMessage(new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'OK'
            ));


//            (new DailyMessageLogger($this->))->set($update);      // логируем сообщение
//            (new JoinToChatLogger($this->client))->set($update);        // todo: если группа старая - надо как-то создать запись о holdtime

            return true;
        } catch (CharsCountException $charsCountException) {
            $errorMessage = 'Максимальное количество символов: ' . $setting->getMaxCharsCount();
        } catch (WordsCountException $wordsCountException) {
            $errorMessage = 'Максимальное количество слов: ' . $setting->getMaxWordsCount();
        } catch (LinkException $linkException) {
            $errorMessage = 'Ссылки запрещенны';
        } catch (ReferralsCountException $referralsCountException) {
            $errorMessage = 'Пригласите больше людей в группу';
        } catch (DailyMessageCountException $dailyMessageCountException) {
            $errorMessage = 'Превышено максимально количество сообщений в сутки';
        } catch (HoldTimeException $holdTimeException) {
            $errorMessage = 'Период молчания не закончился. Подождите.';
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
        }

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $errorMessage
        ));
//        $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId()));

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;
        if (null === $update->getMessage()) return false;

        return $update->getMessage()->getChat()->getId() < 0;
    }
}