<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        Job::factory(100)->create();
        JobUser::factory(100)->create();
    }
}
