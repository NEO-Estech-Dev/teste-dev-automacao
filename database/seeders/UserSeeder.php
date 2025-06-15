<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = Vacancy::all();

        User::factory(90)
            ->create()
            ->each(function ($user) use ($vacancies) {
                $user->vacancies()->attach(
                    $vacancies->random(2)->pluck('id')->toArray()
                );
            });
    }
}
