<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recruiter>
 */
class RecruiterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
        'idUserRecruiter' => User::factory(), // Cria o usuário junto
        'phone' => $this->faker->phoneNumber,
        'nome_empresa' => $this->faker->company,
        'created_at' => now(),
    ];
    }
}
