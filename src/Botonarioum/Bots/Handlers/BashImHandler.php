<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Symfony\Component\DomCrawler\Crawler;

class BashImHandler extends AbstractHandler
{
    public const HANDLER_NAME = 'bot.bashorg.parser';

    public function handle(Bot $bot, Update $update): bool
    {
        $userInput = $update->getMessage()->getText();

        if ($userInput === self::BOTONARIOUM_KEY) {
            $message = $this->botonarioumAction($update);
        } elseif ($userInput === self::DONATE_KEY) {
            $message = $this->donateAction($update);
        } else {
            $randomMessage = $this->getRandom();
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                $randomMessage
            );
            $message->setParseMode('HTML');
        }

        $keyboard = new ReplyKeyboardMarkup(
            [
                [new KeyboardButton('Случайное из BASH.IM')],
                [new KeyboardButton(self::DONATE_KEY), new KeyboardButton(self::BOTONARIOUM_KEY)]
            ]
        );

        $message->setReplyMarkup($keyboard);
        $bot->sendMessage($message);

        return true;
    }

    private function getRandom()
    {
        // ---------------------------------------------------------------------------------------------------------------------
        $crawler = new Crawler();
        $crawler->addHtmlContent(file_get_contents('https://bash.im'));
        $crawler = $crawler->filterXPath('//html/body/div[1]/main/div[3]/input');

        $total = (int)$crawler->attr('value');
        $randomPage = rand(1, $total);
        // ---------------------------------------------------------------------------------------------------------------------
        $crawler = new Crawler();
        $crawler->addHtmlContent(file_get_contents('https://bash.im/index/' . $randomPage));
        $crawler = $crawler->filterXPath('//html/body/div[1]/main/section');

        $quotesCount = $crawler->filter('article')->count();
        $randomQuote = rand(1, $quotesCount);

        $result = '';

        $crawler->filter('article')->each(function (Crawler $node, $i) use ($randomQuote, &$result) {
            if ($i === $randomQuote) {

                $node->filterXPath('./article[1]/div/div')->each(function (Crawler $node, $i) use (&$result) {
                    echo 'ok';
                    $result = $node->html();
                }
                );
            }
        });

        return str_replace('<br>', PHP_EOL, $result);
    }
}