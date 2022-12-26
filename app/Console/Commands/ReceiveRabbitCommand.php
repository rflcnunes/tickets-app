<?php

namespace App\Console\Commands;

use App\Events\ReceiveTicketFromRabbit;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ReceiveRabbitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $connection = new AMQPStreamConnection('172.25.0.1', 5672, 'guest', 'guest');

            $channel = $connection->channel();

            echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

            $callback = function ($msg) {
                event(new ReceiveTicketFromRabbit($msg->body));
//                echo " [x] Received ", $msg->body, "\n";
            };

            $channel->basic_consume('nubank', '', false, true, false, false, $callback);

            while ($channel->is_consuming()) {
                $channel->wait();
            }

            $channel->close();

            $connection->close();

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
