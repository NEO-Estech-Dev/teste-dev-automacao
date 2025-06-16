<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['type' => 'candidate'])->id,
            'vacancy_id' => Vacancy::factory()->create()->id,
            'status' => fake()->randomElement(['pending', 'shortlisted', 'rejected', 'hired']),
            'curriculum_url' => fake()->url()
        ];
    }
}
