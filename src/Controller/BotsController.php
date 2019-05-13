<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BashImBot\BashImBot;
use App\Botonarioum\Bots\BotInterface;
use App\Botonarioum\Bots\BotonarioumBot\BotonarioumBot;
use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController
{
    /**
     * @Route("/bots/{token}", name="handler")
     */
    public function handler(Request $request, string $token): Response
    {
        if ($request->isMethod('post')) {
            $bots = [
                new BashImBot(),
                new BotonarioumBot(),
            ];

            $requestBody = file_get_contents('php://input');
            $data = json_decode($requestBody, true);

            /** @var BotInterface $bot */
            foreach ($bots as $bot) {
                if ($bot->isCurrentBot()) {
                    $bot->handle(Update::create($data));
                    break;
                }
            }
        }

        return new Response('It works! ☺');
    }
}
