<?php

namespace App\Services;

use App\Models\Ticket;
class RabbitService
{
    private $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
    public function getAllTickets()
    {
        // Get all tickets from Rabbit
    }

    public function createTicket($data)
    {
        dump($data);
        $this->ticket->create($data);
    }
}
