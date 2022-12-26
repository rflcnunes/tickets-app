<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;


class PublishRabbitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        try {
            $connection = new AMQPStreamConnection('172.25.0.1', 5672, 'guest', 'guest');

            $channel = $connection->channel();

            $channel->queue_declare('hello', false, false, false, false);

            $msg = new AMQPMessage('Hello World!');

            $channel->basic_publish($msg, '', 'hello');

            echo " [x] Sent 'Hello World!'\n";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }
}
