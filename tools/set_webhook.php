<?php

use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SetWebhook;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$env = new Dotenv();
$env->load(__DIR__ . '/../.env');

$host = readline('NGROK HOST (example: https://d0b66067.ngrok.io): ');
$tokens = [
    $_ENV['SANDBOXBOT_TOKEN'],
];

foreach ($tokens as $token) {
    $setWebhook = new SetWebhook(implode('/', [$host, 'bots', $token]));

    // uncomment if use use self-signed certificate
    $setWebhook->setCertificate(file_get_contents(__DIR__ . '/../tools/cert/cacert.pem'));

    $bot = new Bot($token);
    $bot->setWebhook($setWebhook);
}
