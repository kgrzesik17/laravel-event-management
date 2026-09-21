<?php

namespace App\Models;

use App\Models\Attendee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    public function User(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function attendees(): HasMany {
        return $this->hasMany(Attendee::class);
    }
}
