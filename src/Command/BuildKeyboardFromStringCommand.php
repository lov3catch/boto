<?php

namespace App\Command;

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;
use Formapro\TelegramBot\SendMessage;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildKeyboardFromStringCommand extends Command
{
    protected static $defaultName = 'BuildKeyboardFromString';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


//        $exampleString = '[(Моя ссылка 1: http://example1.com)(Моя ссылка 2, http://example2.com)(Моя ссылка 3, @botonarioum_bot)]';
        $exampleString = '(Моя ссылка 1: http://example1.com)(Моя ссылка 2: http://example2.com)' . PHP_EOL . '(Моя ссылка 3: http://example3.com)';
//        $exampleString = '(Моя ссылка 1: ddfgdfg)';
//        $exampleString = '';


        $keyboard = [];
        try {
            foreach (explode(PHP_EOL, $exampleString) as $item) {
//                $matches = [];
//                $result = preg_match_all('/\((?\'title\'.*?)\:(?\'link\'.*?)\)/', $item, $matches);
//
//                if (!$result) continue;
//
//                $keyboardValues = array_combine($matches['title'], $matches['link']);
//
//                $keyboardLine = [];
//                foreach ($keyboardValues as $title => $link) {
//                    $title = trim($title);
//                    $link = trim($link);
//
//                    $keyboardLine[] = InlineKeyboardButton::withUrl($title, $link);
//                }
//
//                $keyboard[] = $keyboardLine;

                $keyboard[] = $this->buildKeyboardLine($item);
            }

            $markup = new InlineKeyboardMarkup($keyboard);

            $message = new SendMessage(292198768, 'example');
            $message->setReplyMarkup($markup);

            (new Bot('870634261:AAENuD0Y0yTg6g0uncl4vblRl7NYjqmUkeM'))->sendMessage($message);
        } catch (RequestException $exception) {
            (new Bot('870634261:AAENuD0Y0yTg6g0uncl4vblRl7NYjqmUkeM'))->sendMessage($message = new SendMessage(292198768, 'Что-то не так с ссылкой'));
        } catch (\Exception $exception) {
            (new Bot('870634261:AAENuD0Y0yTg6g0uncl4vblRl7NYjqmUkeM'))->sendMessage($message = new SendMessage(292198768, 'Не верный формат записи'));
        }

        return 0;
    }

    private function buildKeyboardLine(string $line)
    {
        $matches = [];
        $result = preg_match_all('/\((?\'title\'.*?)\:(?\'link\'.*?)\)/', $line, $matches);

        if (!$result) throw new \Exception('Не верный формат записи');

        $keyboardValues = array_combine($matches['title'], $matches['link']);

        $keyboardLine = [];
        foreach ($keyboardValues as $title => $link) {
            $title = trim($title);
            $link = trim($link);

            $keyboardLine[] = InlineKeyboardButton::withUrl($title, $link);
        }

        return $keyboardLine;
    }
}
