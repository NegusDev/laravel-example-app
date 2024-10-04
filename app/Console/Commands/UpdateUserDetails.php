<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory;
use Illuminate\Console\Command;

class UpdateUserDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $faker = Factory::create();
        $timezones = ['CET', 'CST', 'GMT+1'];

        $users = User::all();

        foreach ($users as $user)
        {
            $user->first_name = $faker->firstName;
            $user->last_name = $faker->lastName;
            $user->timezone = $faker->randomElement($timezones);
            $user->save();

            $this->info("Updated user {$user->id}: {$user->firstname} {$user->lastname}, timezone: {$user->timezone}");
        }
        return 0;
    }
}
