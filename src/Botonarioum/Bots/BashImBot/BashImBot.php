<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\BashImBot;

use App\Botonarioum\Bots\AbstractBot;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\KeyboardButton;
use Formapro\TelegramBot\ReplyKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Symfony\Component\DomCrawler\Crawler;

class BashImBot extends AbstractBot
{
    protected const ENV_TOKEN_KEY = 'BASHIM_TOKEN';
    /**
     * @var Bot
     */
    private $bot;

    public function __construct()
    {
        $this->bot = new Bot($this->getToken());
    }

    public function handle(Update $update): bool
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

        $fooButton = new KeyboardButton('Случайное из BASH.IM');
        $bazButton = new KeyboardButton(self::BOTONARIOUM_KEY);
        $barButton = new KeyboardButton(self::DONATE_KEY);
        $keyboard = new ReplyKeyboardMarkup([[$fooButton], [$barButton, $bazButton]]);

        $message->setReplyMarkup($keyboard);
        $this->bot->sendMessage($message);

        return true;
    }

    public function getToken(): string
    {
        return $_ENV[self::ENV_TOKEN_KEY];
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