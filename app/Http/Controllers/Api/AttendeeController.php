<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
Use App\Models\Event;
Use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 1
            ])
        );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return AttendeeResource(
            $this->loadRelationsips($attendee)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)  // event is not needed, but route passes arguments one by one, that's why we don't type hint
    {
        $attendee->delete();

        return response(status: 204);
    }
}
