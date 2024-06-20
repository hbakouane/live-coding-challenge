<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\PushNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PushNotificationDevice>
 */
class PushNotificationDevicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'push_notification_id' => PushNotification::factory(),
            'device_id' => Device::factory(),
            'sent_at' => null
        ];
    }
}
