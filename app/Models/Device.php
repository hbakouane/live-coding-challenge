<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'token',
        'is_expired'
    ];

    /**
     * Attributes that will be cast
     */
    protected $casts = [
        'is_expired' => 'boolean'
    ];

    /**
     * A local scope to get only unexpired devices
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereUnexpired(Builder $query)
    {
        return $query->where('is_expired', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pushNotificationsDevices()
    {
        return $this->hasMany(PushNotificationDevice::class);
    }

    /**
     * Marks device as expired
     *
     * @return bool
     */
    public function markAsExpired()
    {
        return self::update([
            'is_expired' => true
        ]);
    }
}
