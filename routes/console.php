<?php

use App\Jobs\GetUserReponseJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\GetUserResponseJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// a schedule to clear deleted post from more than 30 days
Schedule::command('app:clear_deleted_posts')->daily();

// a schedule to get a results from API every six hours
Schedule::job(new GetUserReponseJob)->everySixHours();
