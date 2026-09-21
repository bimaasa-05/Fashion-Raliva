<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('payment:expire')->everyMinute()->withoutOverlapping();
Schedule::command('store:auto-reactivate')->everyMinute()->withoutOverlapping();
Schedule::command('order:auto-complete')->everyMinute()->withoutOverlapping();
