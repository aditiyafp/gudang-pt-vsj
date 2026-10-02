<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Automatic Database Backup at 00:00 WIB every day
Schedule::command('db:backup')
    ->dailyAt('00:00')
    ->timezone('Asia/Jakarta')
    ->appendOutputTo(storage_path('logs/backup.log'));
