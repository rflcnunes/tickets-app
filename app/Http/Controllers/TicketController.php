<?php

namespace App\Http\Controllers;

use App\Services\RabbitService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private $rabbitService;

    public function _construct(RabbitService $rabbitService)
    {
        $this->rabbitService = $rabbitService;
    }

    public function index()
    {
        // Get all tickets
        return response()->json([
            'message' => 'All tickets',
            'data' => $this->rabbitService->getAllTickets()
        ]);

    }

    public function store(Request $request)
    {
        // Create a new ticket
    }
}
