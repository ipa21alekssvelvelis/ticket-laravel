<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\Event;
use App\Models\SoldTickets;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function handlePayment(Request $request)
    {
        \Log::info('Stripe Payment Request:', $request->all());

        $request->validate([
            'amount' => 'required|min:1',
            'token' => 'required',
            'user_token' => 'required',
            'quantity' => 'required',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        $amount = $request->input('amount');
        $ticketId = $request->input('ticket_id');
        $eventId = $request->input('event_id');
        $quantity = $request->input('quantity');
        Log::info(['quantity' => $quantity]);
        $token = $request->input('token');
        $userToken = $request->input('user_token');
        // Log::info('token that was passed:', $token);
            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => ($amount * 100)*$quantity,
                'currency' => 'eur',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $request->input('token')['id'],
                    ],
                ],
                'confirm' => true,
                'return_url' => 'http://localhost:3000', // Replace with your actual return URL
            ]);

            // Log::info('Stripe API Response: ' . json_encode($paymentIntent));
            $userToken = PersonalAccessToken::where('token', $request->input('user_token'))->first();

            Log::info('Token Validation Result: ' . json_encode($userToken));

            if (!$userToken) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $user_id = $userToken->tokenable_id;

            Log::info('User ID: ' . $user_id);

            SoldTickets::create([
                'user_id' => $user_id,
                'ticket_id' => $ticketId,
                'event_id' => $eventId,
                'quantity' => $quantity,
                'payment_intent_id' => $paymentIntent->id,
                // Add any other necessary fields
            ]);

            $ticket = Ticket::find($ticketId);

            if (!$ticket) {
                return response()->json(['error' => 'Ticket not found'], 404);
            }
            $ticket->quantity -= $quantity;
            $ticket->save();

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    
        // return response()->json(['clientSecret' => $intent->client_secret]);
    }
}