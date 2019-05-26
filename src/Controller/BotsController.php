<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BashImBot\BashImBot;
use App\Botonarioum\Bots\BotInterface;
use App\Botonarioum\Bots\BotonarioumBot\BotonarioumBot;
use App\Botonarioum\Bots\SandboxBot\SandboxBot;
use App\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotsController
{
    /**
     * @Route("/bots", name="handler")
     */
    public function index(Request $request): Response
    {
        return new Response('It works! ☺');
    }

    /**
     * @Route("/bots/{token}", name="handler_example")
     */
    public function handler(Request $request, $token, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('post')) {
            $bots = [
                new BashImBot(),
                new BotonarioumBot(),
                new SandboxBot()
            ];

            $requestBody = file_get_contents('php://input');
            $data = json_decode($requestBody, true);

            /** @var BotInterface $bot */
            foreach ($bots as $bot) {
                if ($bot->isCurrentBot()) {
                    $bot->handle(Update::create($data), $entityManager);
                    break;
                }
            }
        }

        $this->saveToDb($data, $token, $entityManager);

        return new Response('It works!! ☺');
    }

    private function saveToDb(array $request, string $token, EntityManagerInterface $entityManager)
    {
        try {
            $channel = new Channel();
            $channel->setToken($token);
            $channel->setChannelId($request['message']['chat']['id']);
            $channel->setFirstName($request['message']['from']['first_name']);
            $channel->setLastName($request['message']['from']['last_name']);
            $channel->setCreatedAt(new \DateTime());
            $channel->setUpdatedAt(new \DateTime());

            $entityManager->persist($channel);
            $entityManager->flush();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

    }
}
