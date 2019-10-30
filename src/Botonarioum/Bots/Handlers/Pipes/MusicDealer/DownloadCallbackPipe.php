<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\CallbackPipe;
use Formapro\TelegramBot\AnswerCallbackQuery;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\InputFile;
use Formapro\TelegramBot\SendDocument;
use Formapro\TelegramBot\Update;
use Requests;

class DownloadCallbackPipe extends CallbackPipe
{
    // todo: проверить почему не используется ссылка для поиска (как реализоано сейчас? сделать по одной схеме)
    private const
        SEARCH_URL = 'https://track-finder.herokuapp.com/search?query={}',
        DOWNLOAD_URL = 'https://track-finder.herokuapp.com/download';

    public function processing(Bot $bot, Update $update): bool
    {
        $refreshCookie = Requests::get('https://zaycev.net', [], ['verify' => false]);
        $refreshCookie->cookies;

        $this->sendAnswer($bot, $update);
        $downloadUrl = $this->buildDownloadUrl($bot, $update);
        $content = Requests::get($downloadUrl, [], ['proxy' => 'http://1aWVou:z06dDD@37.9.36.234:8000', 'cookies' => $refreshCookie->cookies]);
        $inputFile = new InputFile('sound.mp3', $content->body);
        $bot->sendDocument(SendDocument::withInputFile($update->getCallbackQuery()->getMessage()->getChat()->getId(), $inputFile));

        return true;
    }

    private function sendAnswer(Bot $bot, Update $update): void
    {
        $answer = new AnswerCallbackQuery($update->getCallbackQuery()->getId());
        $answer->setText('🔊 Загрузка...');

        $bot->answerCallbackQuery($answer);
    }

    private function buildDownloadUrl(Bot $bot, Update $update): string
    {
        // todo: инкапсулировать логику загрузки в отдельном классе
//        [$providerAlias, $url] = explode('::', $update->getCallbackQuery()->getData());
        $url = $update->getCallbackQuery()->getData();
        $providerAlias = 'zn';
        $provider = ['zn' => 'zaycev_net', 'mr' => 'mail_ru'][$providerAlias];
        $args = ['url' => $url, 'provider' => $provider];
        $content = json_decode(file_get_contents(implode('', [self::DOWNLOAD_URL, '?', http_build_query($args)])), true);

        return $content['data']['download_url'];
    }
}
