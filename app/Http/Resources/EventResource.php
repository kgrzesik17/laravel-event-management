<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_time' => $this->start_time,  // good place to customize the date format
            'end_time' => $this->end_time,

            // displaying the user right away is possible due to resources
            'user' => new UserResource($this->whenLoaded('user')),  // whenLoaded - user property would on be presented if user relationship of particular event is loaded

            'attendees' => AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
}
