<?php

use App\Models\User;
use App\Models\Vacancy;
use function Pest\Laravel\{actingAs, artisan, postJson, assertDatabaseHas};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('migrate');

    $this->applicant = User::factory()->create(['type' => 'applicant']);
    $this->recruiter = User::factory()->create(['type' => 'recruiter']);
    $this->vacancy = Vacancy::factory()->create(['status' => 'open']);
});


it('deve permitir que um candidato se inscreva em uma vaga', function () {
    $payload = ['vacancy_id' => $this->vacancy->id];

    actingAs($this->applicant)
        ->postJson('/api/applicant/apply-to-vacancy/', $payload)
        ->assertStatus(200);

    assertDatabaseHas('user_vacancy', [
        'user_id' => $this->applicant->id,
        'vacancy_id' => $this->vacancy->id,
    ]);
});


it('não deve permitir que um recrutador se inscreva em uma vaga', function () {
    $payload = ['vacancy_id' => $this->vacancy->id];

    actingAs($this->recruiter)
        ->postJson('/api/applicant/apply-to-vacancy/', $payload)
        ->assertStatus(403);
});

it('não deve permitir que um candidato se inscreva na mesma vaga duas vezes', function () {
    $this->applicant->vacancies()->attach($this->vacancy->id);
    $payload = ['vacancy_id' => $this->vacancy->id];

    actingAs($this->applicant)
        ->postJson('/api/applicant/apply-to-vacancy/', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors('vacancy_id');
});

it('não deve permitir que um usuário não autenticado se inscreva em uma vaga', function () {
    $payload = ['vacancy_id' => $this->vacancy->id];

    postJson('/api/applicant/apply-to-vacancy/', $payload)
    ->assertStatus(401);
});