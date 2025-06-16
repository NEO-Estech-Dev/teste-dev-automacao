<?php

namespace Database\Factories;

use App\Enums\VacancyType;
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
            'type' => fake()->randomElement(VacancyType::values()),
            'description' => fake()->paragraph(),
            'company_name' => fake()->company(),
            'salary' => fake()->numberBetween(1000, 10000),
            'status' => fake()->randomElement(['active', 'inactive']),
            'recruiter_id' => User::factory()->create(['type' => 'recruiter'])->id
        ];
    }
}
