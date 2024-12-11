<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use \Exception;

class GetUserReponseJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::withOptions(['verify' => false])->get('https://randomuser.me/api/');

            if ($response->successful()) {

                $results = $response->json('results');
                Log::info('Results: ', $results);
            } else {
                Log::error('Failed to fetch data from the API.');
            }
        } catch (Exception $e) {
            Log::error('An error occurred while fetching data from the API: ' . $e->getMessage());
        }
    }
}
