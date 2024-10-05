<?php

use App\Models\User;
use Faker\Factory;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {
    Log::info('Scheduled task triggered');

    $client = new Client();
    $faker = Factory::create();
    $request_limit = 10;
    $api_url = "https://dummyjson.com";
    $batch_limit = 50;
    $timezones = ['CET', 'CST', 'GMT+1'];


    for ($i = 1; $i <= $request_limit; $i++) {

        $skip = ($i - 1) * $batch_limit;
        $url = "{$api_url}/users?limit={$batch_limit}&skip={$skip}";
        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);
            foreach ($data['users'] as $user) {
                User::updateOrCreate(
                    ['email' => $user['email']],
                    ['first_name' => $user['firstName'], 'last_name' => $user['lastName'], 'email' => $user['email'], 'timezone' => $faker->randomElement($timezones)]
                );

                Log::info("Updated or created a new user instance");
            }
        } catch (Exception $e) {
            Log::error("Failed to fetch batch {$i}: " . $e->getMessage());
        }
    }
})->hourly();
