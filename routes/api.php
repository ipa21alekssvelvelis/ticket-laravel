<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventTypesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/event-types', [EventTypesController::class, 'fetchData']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

use App\Http\Controllers\EventController;

Route::post('/event-store', [EventController::class, 'store']);
Route::get('/event-list', [EventController::class, 'eventList']);
Route::delete('/event-delete-{id}', [EventController::class, 'delete']);
Route::put('/event-update-{id}', [EventController::class, 'edit']);

use App\Http\Controllers\ReviewController;

Route::post('/submit-review', [ReviewController::class, 'submitReview']);
Route::get('/review-list-{id}', [ReviewController::class, 'getReviewsForEvent']);