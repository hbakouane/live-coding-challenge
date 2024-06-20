<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'sending_started_at'
    ];

    /**
     * Attributes that will be cast
     */
    protected $casts = [
        'sending_started_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pushNotificationsDevices()
    {
        return $this->hasMany(PushNotificationDevice::class);
    }

    /**
     * Mark notification as started sending
     *
     * @return bool
     */
    public function markSendingAsStarted()
    {
        return self::update([
            'sending_started_at' => now()
        ]);
    }
}
