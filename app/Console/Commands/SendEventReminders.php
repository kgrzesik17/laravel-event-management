<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;


class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders'; // what you type in the CLI after php artisan

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that the event starts soon.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // logic goes here
        $events = \App\Models\Event::with('attendees.user')  // nested loading of connected models
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();  // does not query, just returns the number of items in the collection
        $eventLabel = Str::plural('event', $eventCount);

        $this->info("Found {$eventCount} {$eventLabel}.");

        $events->each(  // each - closure function on every event inside the collection
            fn ($event) => $event->attendees->each(
                fn ($attendee) => $this->info("Notifying the user {$attendee->user->id}")
            )
        );

        $this->info('Reminder notifications sent successfully!');
    }
}
