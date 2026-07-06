<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tenant-scoped operational jobs must run inside each tenant's DB context.
// tenants:run-scoped iterates operational tenants and isolates per-tenant errors.
Schedule::command('tenants:run-scoped rentals:check-late')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/late-rentals.log'));

Schedule::command('tenants:run-scoped app:send-rental-reminders')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/rental-reminders.log'));

Schedule::command('tenants:run-scoped finance:run-depreciation')
    ->lastDayOfMonth('23:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/depreciation.log'));

Schedule::command('tenants:run-scoped rentals:generate-recurring')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/recurring-rentals.log'));

Schedule::command('tenants:run-scoped maintenance:flag-due')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/maintenance-flag-due.log'));

Schedule::command('subscriptions:generate-invoices')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/subscription-invoices.log'));

Schedule::command('subscriptions:check-status')
    ->dailyAt('00:30')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/subscription-status.log'));
