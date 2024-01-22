<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tickets' => 'required|array',
            'tickets.*.event_id' => 'required|exists:events,id',
            'tickets.*.name' => 'required',
            'tickets.*.price' => 'required|numeric|min:1',
            'tickets.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Input missing', 'details' => $validator->errors()], 422);
        }

        $ticketsData = $request->input('tickets');

        foreach ($ticketsData as $ticketData) {
            Ticket::create([
                'event_id' => $ticketData['event_id'],
                'ticket_name' => $ticketData['name'],
                'ticket_price' => $ticketData['price'],
                'quantity' => $ticketData['quantity'],
            ]);
        }

        return response()->json(['message' => 'Tickets created successfully'], 200);
    }
    public function getTicketsForEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $tickets = Ticket::where('event_id', $id)->get();

        return response()->json($tickets);
    }

    public function updateOrCreateTickets(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        Log::info('Request Data: ' . json_encode($request->all(), JSON_PRETTY_PRINT));

        // $validator = Validator::make($request->all(), [
        //     'tickets' => 'required|json',
        //     'tickets.*.event_id' => 'required|exists:events,id',
        //     'tickets.*.id' => 'sometimes|required|exists:tickets,id',
        //     'tickets.*.ticket_name' => 'required',
        //     'tickets.*.ticket_price' => 'required|numeric|min:1',
        //     'tickets.*.quantity' => 'required|integer|min:1',
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['error' => 'Input missing or invalid', 'details' => $validator->errors()], 422);
        // }

        Log::info('Request Data: ' . json_encode($request->all()));
        // Log::info($validator->errors());

        $ticketsData = $request->json('tickets');

        foreach ($ticketsData as $ticketData) {
            if (isset($ticketData['id'])) {
                // Update existing ticket
                $existingTicket = Ticket::find($ticketData['id']);
    
                if (!$existingTicket) {
                    return response()->json(['error' => 'Ticket not found'], 404);
                }
    
                $existingTicket->update([
                    'event_id' => $id,
                    'ticket_name' => $ticketData['ticket_name'],
                    'ticket_price' => $ticketData['ticket_price'],
                    'quantity' => $ticketData['quantity'],
                ]);
            } else {
                Ticket::create([
                    'event_id' => $id,
                    'ticket_name' => $ticketData['ticket_name'],
                    'ticket_price' => $ticketData['ticket_price'],
                    'quantity' => $ticketData['quantity'],
                ]);
            }
        }

        return response()->json(['message' => 'Tickets created successfully'], 200);
    }

    
}
