<?php

use App\Models\User;
use function Pest\Laravel\{actingAs,
    artisan,
    assertSoftDeleted,
    postJson,
    assertDatabaseHas,};
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('migrate');
    $this->recruiter = User::factory()->create(['type' => 'recruiter']);
});


it('deve retornar todos os usuários para um recrutador autenticado', function () {
    User::factory()->count(10)->create();

    actingAs($this->recruiter)
        ->getJson('/api/user')
        ->assertStatus(200)
        ->assertJsonCount(11, 'data');
});


it('deve permitir a criação de um novo usuário', function () {
    $userData = [
        'name' => 'Novo Candidato',
        'email' => 'candidato@email.com',
        'password' => 'password123',
        'type' => 'candidato',
    ];

    postJson('/api/user', $userData)
        ->assertStatus(201)
        ->assertJsonFragment(['name' => 'Novo Candidato']);

    assertDatabaseHas('users', [
        'email' => 'candidato@email.com',
    ]);
});


it('deve permitir que um recrutador atualize um usuário', function () {
    $userToUpdate = User::factory()->create();

    actingAs($this->recruiter)
        ->putJson('/api/user/' . $userToUpdate->id, ['name' => 'Nome Atualizado'])
        ->assertStatus(200)
        ->assertJsonFragment(['name' => 'Nome Atualizado']);

    assertDatabaseHas('users', [
        'id' => $userToUpdate->id,
        'name' => 'Nome Atualizado'
    ]);
});


it('deve permitir que um recrutador delete um usuário', function () {
    $userToDelete = User::factory()->create();

    actingAs($this->recruiter)
        ->deleteJson('/api/user/' . $userToDelete->id)
        ->assertStatus(204);

    assertSoftDeleted('users', [
        'id' => $userToDelete->id,
    ]);
});