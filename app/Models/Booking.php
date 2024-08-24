<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;



    public function scheduledNotifications(): MorphMany
    {
        return $this->morphMany(ScheduledNotification::class, 'notifiable');
    }
}
