<?php

use App\Models\User;
use function Pest\Laravel\{artisan, postJson, assertAuthenticatedAs, assertGuest};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('migrate');

    $this->user = User::factory()->create([
        'email' => 'usuario@teste.com',
        'password' => Hash::make('senha123'),
    ]);
});



it('deve autenticar um usuário com credenciais válidas e retornar um token', function () {
    $credentials = [
        'email' => 'usuario@teste.com',
        'password' => 'senha123',
    ];

    postJson('/api/login', $credentials)
        ->assertStatus(200);
    assertAuthenticatedAs($this->user);
});

it('não deve autenticar um usuário com senha incorreta', function () {
    $credentials = [
        'email' => 'usuario@teste.com',
        'password' => 'senha-errada',
    ];

    postJson('/api/login', $credentials)
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');

    assertGuest();
});

it('não deve autenticar um usuário com email inexistente', function () {
    $credentials = [
        'email' => 'naoexiste@teste.com',
        'password' => 'qualquersenha',
    ];

    postJson('/api/login', $credentials)
        ->assertStatus(422)
        ->assertJsonValidationErrors('email');

    assertGuest();
});

it('deve fazer o logout de um usuário autenticado', function () {
    $token = $this->user->createToken('test-token')->plainTextToken;

    postJson('/api/logout', [], [
        'Authorization' => 'Bearer ' . $token,
    ])->assertStatus(204);

    expect($this->user->fresh()->tokens)->toHaveCount(0);
});