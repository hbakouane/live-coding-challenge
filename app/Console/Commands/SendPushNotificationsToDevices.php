<?php

namespace App\Console\Commands;

use App\Models\PushNotificationDevice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Jobs\SendPushNotificationsToDevices as SendPushNotificationsToDevicesJob;

class SendPushNotificationsToDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push-notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends push notifications to the correspondent devices.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*
         * The scheduler will execute this command every 1 minute (as stated in the READme).
         * We will retrieve 1000 notifications from the DB every minute
         * and batch-send them all
         */

        $this->comment('Scheduling notifications has initiated.');

        // Get notifications of unexpired devices
        $pushNotificationsDevices = PushNotificationDevice::query()
            ->with(['pushNotification', 'device']) // Eager load relationships because we will need them in the Job class
            ->whereHas('device', function ($query) {
                return $query->where('is_expired', false);
            })->limit(1000)->get();

        // Hande the push notifications to a queue job to send them to devices
        SendPushNotificationsToDevicesJob::dispatch($pushNotificationsDevices);

        $this->info('A batch of ' . count($pushNotificationsDevices) . ' notifications was scheduled to be sent.');
    }
}
