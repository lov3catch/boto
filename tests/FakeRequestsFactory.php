<?php declare(strict_types=1);

namespace App\Tests;

use Formapro\TelegramBot\Update;

class FakeRequestsFactory
{
    public static function createStartRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/start.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function createMessageAsHelloWorld(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/message_as_helloworld.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function creatMessageAsSongNameRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/message_as_songname.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function creatDonateRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/donate.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function creatDownloadRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/download.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function creatBotonarioumRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/botonarioum.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function createNextPageRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/next.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }

    public static function createPrevPageRequest(): Update
    {
        $file = new \SplFileObject(__DIR__ . '/requests/prev.json');
        $json = json_decode($file->fread($file->getSize()), true);

        return Update::create($json);
    }
}
