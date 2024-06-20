<?php

namespace Database\Seeders;

use App\Console\Commands\SendPushNotificationsToDevices;
use App\Models\Device;
use App\Models\PushNotification;
use App\Models\PushNotificationDevice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PushNotificationDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pushNotification = PushNotification::factory()->create();

        $this->command->info('One notification was created to be attached to the devices that will be created.');

        $devicesCount = $this->command->ask('How many devices would you like to create?', 1000);

        $devices = Device::factory($devicesCount)->create();

        $devices->each(function ($device) use ($pushNotification, &$pushNotificationsCreatedCount) {
            PushNotificationDevice::create([
                'push_notification_id' => $pushNotification->id,
                'device_id' => $device->id,
                'sent_at' => null
            ]);

            $pushNotificationsCreatedCount++;
        });

        $this->command->info('One notification created.');
        $this->command->info($devicesCount . ' devices created.');
        $this->command->info($pushNotificationsCreatedCount . ' push notifications created.');

        $answer = $this->command->ask('Would you like to proceed with running the command SendPushNotificationsToDevices? (Y/N)', 'N');

        if ($answer === 'Y') {
            $this->command->call(SendPushNotificationsToDevices::class);
        }
    }
}
