<?php

namespace App\Jobs;

use App\Models\PushNotificationDevice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SendPushNotificationsToDevices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Collection $pushNotificationsDevices
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Start DB transaction so that if an error occurs, we revert DB changes.
        DB::transaction(function () {
            /** @var PushNotificationDevice $pushNotificationDevice */
            $pushNotificationsDevices = $this->pushNotificationsDevices;

            foreach ($pushNotificationsDevices as $pushNotificationDevice) {
                if ($pushNotificationDevice->sendNotification()) {
                    // Mark device as expired
                    $pushNotificationDevice->device->markAsExpired();

                    // Check if the sending has already started for this notification
                    if (!$pushNotificationDevice->pushNotification->sending_started_at) {
                        // And start it if it hasn't
                        $pushNotificationDevice->pushNotification->markSendingAsStarted();
                    }
                }
            }
        });
    }

    /**
     * Give a good and easy-understandable log
     * infos about the failure of the job
     *
     * @param \Throwable|null $throwable
     * @return void
     */
    public function failed(?\Throwable $throwable)
    {
        dump('Notifications batch job failed, reason: ' . $throwable->getMessage());

        $logId = Str::random();

        dump('Check logs to know more – log id: ' . $logId);

        Log::info($logId . ' – Job failed (at ' . now() . '): ', $throwable->getTrace());
    }
}
