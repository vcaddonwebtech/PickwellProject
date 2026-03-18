<?php

use App\Console\Commands\SalseReminder;
use App\Console\Commands\TodoReminderCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(TodoReminderCommand::class)->everyMinute();
Schedule::command(SalseReminder::class)->everyMinute();
