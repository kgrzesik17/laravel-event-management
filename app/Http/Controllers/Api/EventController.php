<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Event::query(), $this->relations);

        return EventResource::collection(  // loading all events together with user relationships
            $query->latest()->paginate()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([  // spread operatror '...' will copy all the elements from an old array to a new array
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($this->loadRelationships($event));  // good practice
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',  // will check next validation constraints if value is present in the input
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
            ])
        );

        return EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)  // route model binding instead of $id
    {
        $event->delete();

        // return response()->json([  // acceptable
        //     'message' => 'Event deleted successfully'
        // ]);

        return response(status: 204);  // also acceptable - no content status
    }
}
