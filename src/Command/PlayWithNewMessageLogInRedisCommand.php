<?php

namespace App\Command;

use App\Botonarioum\Bots\Helpers\RedisKeys;
use App\Storages\RedisStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PlayWithNewMessageLogInRedisCommand extends Command
{
    protected static $defaultName = 'PlayWithNewMessageLogInRedis';
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
        $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey(1), [1]);
        $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey(1), [2]);
        $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey(1), [3]);

        $elements = array_unique($this->client->lrange(RedisKeys::makeLastGreetingsMessageIdKey(1), 0, -1));

        $this->client->del([RedisKeys::makeLastGreetingsMessageIdKey(1)]);

        foreach ($elements as $element) {
            try {
                echo 'MODERATOR GREETING TO REMOVE: ' . $element . PHP_EOL;
            } catch (\Exception $exception) {
                $this->client->lpush(RedisKeys::makeLastGreetingsMessageIdKey(1), $element);
            }
        }

        var_dump('--------------------' . PHP_EOL);
        var_dump($this->client->lrange(RedisKeys::makeLastGreetingsMessageIdKey(1), 0, -1));


        die;


        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
