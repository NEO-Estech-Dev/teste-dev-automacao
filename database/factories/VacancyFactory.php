<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacancy>
 */
class VacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'salary' => fake()->randomFloat(2, 1500, 10000),
            'type' => fake()->randomElement(['employee', 'independent_contractor', 'freelancer']),
            'status' => fake()->randomElement(['open', 'closed', 'paused']),
            'recruiter_id' => User::where('type', 'recruiter')->inRandomOrder()->first()->id,
        ];
    }
}
