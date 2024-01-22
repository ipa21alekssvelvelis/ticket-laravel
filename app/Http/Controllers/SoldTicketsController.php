<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PersonalAccessToken;
use App\Models\Event;
use App\Models\SoldTickets;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SoldTicketsController extends Controller
{
    public function getPurchaseHistoryForUser(Request $request, $userId)
    {
        $bearerToken = $request->header('Authorization');
    
        Log::info('Bearer Token:', ['token' => $bearerToken]);

        
        $userToken = PersonalAccessToken::where('token', $bearerToken)->first();

        Log::info('User Token:', ['userToken' => $userToken]);

        if (!$userToken) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $tokenableId = $userToken->tokenable_id;

        Log::info('tokenable id:',['tokenable_id' => $tokenableId]);

        $query = SoldTickets::where('user_id', $tokenableId)->with(['event', 'ticket']);
        Log::info('SQL Query:', ['query' => $query->toSql()]);

        $purchaseHistory = SoldTickets::where('user_id', $tokenableId)
            ->with(['event', 'ticket'])
            ->get(['id', 'user_id', 'event_id', 'ticket_id', 'quantity', 'payment_intent_id']);

        return response()->json(['data'=>$purchaseHistory]);
        return response()->json(['user_id' => $userId]);
        
    }
}
