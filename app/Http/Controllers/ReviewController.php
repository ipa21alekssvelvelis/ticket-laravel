<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviews;
use App\Models\Event;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {;
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'user_review' => 'required|string',
            'user_rating' => 'required|integer|between:1,5',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Input missing', 'details' => $validator->errors()], 422);
        }

        $userToken = PersonalAccessToken::where('token', $request->input('token'))->first();

        if (!$userToken) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        
        $user_id = $userToken->tokenable_id;

        $review = Reviews::create([
            'user_id' => $user_id,
            'event_id' => $request->input('event_id'),
            'review' => $request->input('user_review'),
            'rating' => $request->input('user_rating'),
        ]);

        return response()->json(['message' => 'Review submitted successfully'], 200);
    }

    public function getReviewsForEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Specify the columns you want from the user table
        $reviews = $event->reviews()->with(['user' => function ($query) {
            $query->select('id', 'email'); // Specify the columns you want
        }])->get(['id', 'user_id', 'event_id', 'review', 'rating']);

        return response()->json($reviews);
    }
}
