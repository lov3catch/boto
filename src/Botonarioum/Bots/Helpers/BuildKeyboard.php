<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Helpers;

use Formapro\TelegramBot\InlineKeyboardButton;
use Formapro\TelegramBot\InlineKeyboardMarkup;

class BuildKeyboard
{
    public function build(string $keyboardAsString): InlineKeyboardMarkup
    {
        $keyboard = [];
        foreach (explode(PHP_EOL, $keyboardAsString) as $item) {
            $keyboard[] = $this->buildKeyboardLine($item);
        }

        return new InlineKeyboardMarkup($keyboard);
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
