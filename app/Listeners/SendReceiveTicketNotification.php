<?php

namespace App\Listeners;

use App\Events\ReceiveTicketFromRabbit;
use App\Services\RabbitService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendReceiveTicketNotification
{
    private $rabbitService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RabbitService $rabbitService)
    {
        $this->rabbitService = $rabbitService;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\ReceiveTicketFromRabbit $event
     * @return void
     */
    public function handle(ReceiveTicketFromRabbit $event)
    {
        try {
            $message = json_decode($event->getData(), true);

            $data = [
                'sender' => $message['from'] ?? '',
                'receiver' => $message['to'][0] ?? '',
                'title' => $message['subject'] ?? '',
                'description' => $this->formatterData($message['body']) ?? '',
                'status' => 'new ticket',
            ];

            if (in_array('', $data)) {
                $data['status'] = 'pre ticket';
            }

            $this->rabbitService->createTicket($data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function formatterData($data)
    {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data);
    }
}
