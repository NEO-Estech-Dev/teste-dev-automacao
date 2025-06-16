<?php

use App\Models\User;
use App\Models\Vacancy;
use function Pest\Laravel\{actingAs,
    artisan,
    assertSoftDeleted,
    assertDatabaseHas
};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('migrate');
    $this->recruiter = User::factory()->create(['type' => 'recruiter']);
    $this->applicant = User::factory()->create(['type' => 'applicant']);
});

it('deve retornar uma lista paginada de vagas', function () {
    Vacancy::factory()->count(20)->create();

    actingAs($this->recruiter)
        ->getJson('/api/vacancy')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'salary' , 'type', 'status']
                ],
                'links',
            ])
            ->assertJsonCount(20, 'data');
});

it('deve permitir que um recrutador crie uma nova vaga', function () {
    $vacancyData = [
        'title' => 'Desenvolvedor PHP Sênior',
        'description' => 'Trabalhar com Laravel e PestPHP.',
        'salary' => '8000',
        'type' => 'independent_contractor',
        'status' => 'open',
        'recruiter_id' => $this->recruiter->id
    ];

    actingAs($this->recruiter)
        ->postJson('/api/vacancy', $vacancyData)
        ->assertStatus(201)
        ->assertJsonFragment(['title' => 'Desenvolvedor PHP Sênior']);
    assertDatabaseHas('vacancies', ['title' => 'Desenvolvedor PHP Sênior']);
});

it('não deve permitir que um candidato crie uma vaga', function () {
    $vacancyData = ['title' => 'Vaga Ilegal'];

    actingAs($this->applicant)
        ->postJson('/api/vacancy', $vacancyData)
        ->assertStatus(403);
});



it('deve permitir que um recrutador atualize uma vaga', function () {
    $vacancy = Vacancy::factory()->create(['recruiter_id' => $this->recruiter->id]);
    $updateData = ['title' => 'Título da Vaga Atualizado'];

    actingAs($this->recruiter)
        ->putJson('/api/vacancy/' . $vacancy->id, $updateData)
        ->assertStatus(200)
        ->assertJsonFragment($updateData);
    assertDatabaseHas('vacancies', ['id' => $vacancy->id, 'title' => 'Título da Vaga Atualizado']);
});

it('deve permitir que um recrutador delete uma vaga', function () {
    $vacancy = Vacancy::factory()->create(['recruiter_id' => $this->recruiter->id]);

    actingAs($this->recruiter)
        ->deleteJson('/api/vacancy/' . $vacancy->id)
        ->assertStatus(204);
    assertSoftDeleted('vacancies', ['id' => $vacancy->id]);
});
