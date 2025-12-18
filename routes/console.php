<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('orders:cancel-unpaid')
        ->hourly()
        ->withoutOverlapping()
        ->runInBackground()    
        ->onSuccess(function () {
            Log::info('✅ Scheduler: orders:cancel-unpaid completed successfully');
        })
        ->onFailure(function () {
            Log::error('❌ Scheduler: orders:cancel-unpaid FAILED');
        });
