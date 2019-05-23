<?php declare(strict_types=1);

namespace App\Controller;

use App\Botonarioum\Bots\BashImBot\BashImBot;
use App\Botonarioum\Bots\BotInterface;
use App\Botonarioum\Bots\BotonarioumBot\BotonarioumBot;
use App\Entity\Channel;
use Doctrine\ORM\EntityManager;
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

        $this->saveToDb($token, $entityManager);

        return new Response('It works!! ☺');
    }

    private function saveToDb($token, EntityManagerInterface $entityManager)
    {
        try {
            $channel = new Channel();
            $channel->setToken($token);
            $channel->setChannelId(111);
            $channel->setFirstName('example');
            $channel->setLastName('example');
            $channel->setCreatedAt(new \DateTime());
            $channel->setUpdatedAt(new \DateTime());

            $entityManager->persist($channel);
            $entityManager->flush();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

    }
}
