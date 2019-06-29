<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BotonarioumHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'bot.botonarioum.catalogue';

    private const
        CONTACTS_KEY = 'ℹ️ Контакты',
        BOTS_CATALOGUE_KEY = '📔 Боты',
        GROUPS_CATALOGUE_KEY = '📔 Групы';

    private const
        TYPE_BOT_ID = 2,
        TYPE_CHANNEL_ID = 1;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function defaultKeyboard(): ReplyKeyboardMarkup
    {
        return new ReplyKeyboardMarkup([[new KeyboardButton(self::BOTS_CATALOGUE_KEY), new KeyboardButton(self::GROUPS_CATALOGUE_KEY)], [new KeyboardButton(self::DONATE_KEY), new KeyboardButton(self::CONTACTS_KEY)]]);
    }

    public function handle(Bot $bot, Update $update): bool
    {
        $userInput = $update->getMessage()->getText();

        if ($userInput === self::CONTACTS_KEY) {
            $message = $this->contactAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());
        } elseif ($userInput === self::DONATE_KEY) {
            $message = $this->donateAction($update);

            $message->setReplyMarkup($this->defaultKeyboard());;
        } elseif ($userInput === self::BOTS_CATALOGUE_KEY) {
            $message = new SendMessage($update->getMessage()->getChat()->getId(), ' Список ботов:');

            $markup = $this->buildKeyboard($this->entityManager->getRepository(Element::class)->findBy(['type' => self::TYPE_BOT_ID]));

            $message->setReplyMarkup($markup);
        } elseif ($userInput === self::GROUPS_CATALOGUE_KEY) {
            $message = new SendMessage($update->getMessage()->getChat()->getId(), ' Список груп:');

            $markup = $this->buildKeyboard($this->entityManager->getRepository(Element::class)->findBy(['type' => self::TYPE_CHANNEL_ID]));

            $message->setReplyMarkup($markup);
        } else {
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                'Выбери что-то из меню :)'
            );

            $message->setReplyMarkup($this->defaultKeyboard());
        }

        $bot->sendMessage($message);

        return true;
    }

    /**
     * @param Element[] $elements
     * @return InlineKeyboardMarkup
     */
    private function buildKeyboard(array $elements): InlineKeyboardMarkup
    {
        $keyboard = array_map(function (Element $element) {
            return [InlineKeyboardButton::withUrl($element->getName(), $element->getUrl())];
        }, $elements);

        return new InlineKeyboardMarkup($keyboard);
    }
}