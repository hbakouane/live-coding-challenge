<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('push_notification_devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('push_notification_id')
                ->constrained();
            $table->foreignId('device_id')
                ->constrained();

            $table->dateTime('sent_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notification_devices');
    }
};
