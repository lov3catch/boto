<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\AbstractPipe;
use App\Botonarioum\Bots\Helpers\GetMe;
use App\Botonarioum\Bots\Helpers\IsChatAdministrator;
use App\Entity\ModeratorStart;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\ReplyKeyboardRemove;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class StartPipe extends AbstractPipe
{
    /**
     * @var EntityManager
     * @example
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public const
        GROUPS_KEY = '👥 Мои группы';

    public function processing(Bot $bot, Update $update): bool
    {
        // Если это в группе - то...
        if ($update->getMessage()->getChat()->getId() < 0) {
            $isUserAdmin = (new IsChatAdministrator($bot, $update->getMessage()->getChat()))->checkUser($update->getMessage()->getFrom());

            // Если /start пытается вызвать НЕ админ - игнорим
            if (false === $isUserAdmin) {
                return true;
            }

            // Если /start вызывает админ - удаляем клавиатуру (т.к. многие повызывали клавиатуру в чатах)
            if (true === $isUserAdmin) {

                $message = (new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Настройки бота лучше производить в личном чате с ботом :)'
                ));

                $message->setReplyMarkup(new ReplyKeyboardRemove());

                $bot->sendMessage($message);

                return true;
            }
        }

        $text = 'Привет! Это бот-модератор для твоих групп. Добавь его в свою группу, дай права админа (не волнуйся, бот не сделает ничего плохого). Затем настрой его по своему вкусу, либо используй готовые настройки и пользуйся.';
        $message = new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $text
        );

        $keyboard = new ReplyKeyboardMarkup(
            [
                [new KeyboardButton(self::GROUPS_KEY)],
            ]
        );

        $message->setReplyMarkup($keyboard);
        $bot->sendMessage($message);

        $botInfo = (new GetMe())->me($bot);

        /** @var ModeratorStart $moderatorStart */
        $moderatorStartRepository = $this->em->getRepository(ModeratorStart::class);
        $moderatorStart = $moderatorStartRepository->getOrCreate($botInfo->getId(), $update->getMessage()->getFrom()->getId());

        $moderatorStart->setUpdatedAt(new \DateTimeImmutable());

        $moderatorStartRepository->save($moderatorStart);

        return true;
    }

    public function isSupported(Update $update): bool
    {
        if ($update->getCallbackQuery()) return false;

        if ($update->getMessage()) {
            return '/start' === $update->getMessage()->getText() ? true : false;
        }

        return false;
    }
}