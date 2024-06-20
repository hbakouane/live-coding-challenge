<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotificationDevice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'push_notification_id',
        'device_id',
        'sent_at'
    ];

    /**
     * Attributes that will be casted
     */
    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function pushNotification()
    {
        return $this->belongsTo(PushNotification::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function sendNotification()
    {
        // The logic of sending a notification should be here.

        return self::update([
            'sent_at' => true
        ]);
    }
}
