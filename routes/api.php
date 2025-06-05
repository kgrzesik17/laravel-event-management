<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('events', EventController::class);

Route::apiResource('events.attendees', AttendeeController::class)
    // ->scoped(['attendee' => 'event']);  // scope makes sure attendee are always a part or an event - breaks stuff
    ->scoped()->except(['update']);  // this way there's no update route


