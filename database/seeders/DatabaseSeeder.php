<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Device;
use App\Models\PushNotification;
use App\Models\PushNotificationDevice;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $country = Country::factory()->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'country_id' => $country->id
        ]);

        $device = Device::factory()->create([
            'user_id' => $user->id
        ]);

        $pushNotification = PushNotification::factory()->create([
            'user_id' => $user->id
        ]);

        PushNotificationDevice::create([
            'push_notification_id' => $pushNotification->id,
            'device_id' => $device->id
        ]);
    }
}
