<?php

namespace App\Command;

use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\DeleteMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModeratorDeleteGreetingMessagesCommand extends Command
{
    protected static $defaultName = 'ModeratorDeleteGreetingMessages';
    /**
     * @var \Predis\Client
     */
    private $client;

    public function __construct(RedisStorage $redisStorage, string $name = null)
    {
        parent::__construct($name);
        $this->client = $redisStorage->client();
    }


    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        while (true) {
            try {
                $this->clear();
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
            }
        }

//        $elements = array_unique($this->client->lrange(RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()), 0, -1));
//
//        $this->client->del([RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId())]);
//
//        foreach ($elements as $element) {
//            try {
//                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;
//
//                $bot->deleteMessage(new DeleteMessage($update->getMessage()->getChat()->getId(), (int)$element));
//            } catch (\Exception $exception) {
//                $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()), $element);
//            }
//        }

//
//        $greetingsRedisKey = RedisKeys::makeLastGreetingsMessageQueueIdKey();
////        $this->client->del([$greetingsRedisKey]);
////       die;
//
//
//        $lastGreeting = $this->client->lpop($greetingsRedisKey);
//        $otherGreeting = array_unique($this->client->lrange($greetingsRedisKey, 0, -1));
//
//        $this->client->del([$greetingsRedisKey]);
//
//        foreach ($otherGreeting as $greeting) {
//            try {
////                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;
//
//                $data = json_decode($greeting, true);
//
//                $bot = new Bot($data['token']);
//                $groupId = $data['group_id'];
//                $messageId = $data['message_id'];
//
//                $bot->deleteMessage(new DeleteMessage($groupId, $messageId));
//            } catch (\Exception $exception) {
//                var_dump($exception->getMessage());
////                $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()), $element);
//            }
//        }
//
//        $this->client->lpush($greetingsRedisKey, [$lastGreeting]);

//
//        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }

    private function clear(): void
    {
        $greetingsRedisKey = RedisKeys::makeLastGreetingsMessageQueueIdKey();
//        $this->client->del([$greetingsRedisKey]);
//       die;


        $lastGreeting = $this->client->lpop($greetingsRedisKey);
        $otherGreeting = array_unique($this->client->lrange($greetingsRedisKey, 0, -1));

        $this->client->del([$greetingsRedisKey]);

        foreach ($otherGreeting as $greeting) {
            try {
//                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;

                $data = json_decode($greeting, true);

                $bot = new Bot($data['token']);
                $groupId = $data['group_id'];
                $messageId = $data['message_id'];

                $bot->deleteMessage(new DeleteMessage($groupId, $messageId));
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
//                $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey($update->getMessage()->getChat()->getId()), $element);
            }
        }

        $this->client->rpush($greetingsRedisKey, [$lastGreeting]);
    }
}
