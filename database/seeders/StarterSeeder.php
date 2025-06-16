<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vacancy;
use App\Models\Candidate;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidate = User::factory()->create([
            'name' => 'Candidato Teste',
            'email' => 'candidato@example.com',
            'type' => 'candidate',
        ]);

        $recruiter = User::factory()->create([
            'name' => 'Recrutador Teste',
            'email' => 'recrutador@example.com',
            'type' => 'recruiter',
        ]);

        $vacancies = Vacancy::factory(3)->create([
            'recruiter_id' => $recruiter->id,
        ]);

        Candidate::create([
            'user_id' => $candidate->id,
            'vacancy_id' => $vacancies[0]->id,
            'status' => 'pending',
            'curriculum_url' => 'https://example.com/curriculo.pdf',
        ]);
    }
}
