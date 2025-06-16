<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'recruiter@test.com',
            'password' => bcrypt('password'),
            'type' => 'recruiter'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'recruiter@test.com',
            'password' => 'password'
        ]);

        $this->token = $response->json('token');
    }

    public function testListUsersReturnsUsers()
    {
        User::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/user/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'email',
                    'type'
                ]
            ]);
    }

    public function testUpdateUserValidatesInput()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/user/update/' . $this->user->id, [
                'email' => 'invalid-email'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function testUpdateUserUpdatesSuccessfully()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/user/update/' . $this->user->id, [
                'name' => 'Updated Name',
                'email' => 'updated@test.com'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully',
                'user' => [
                    'name' => 'Updated Name',
                    'email' => 'updated@test.com',
                    'type' => 'recruiter'
                ],
                'status' => 200
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com'
        ]);
    }

    public function testDeleteUserDeletesSuccessfully()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/user/delete/' . $this->user->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $this->user->id]);
    }

    public function testBulkDeleteUsersDeletesSuccessfully()
    {
        $users = User::factory()->count(3)->create();
        $userIds = $users->pluck('id')->toArray();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/user/bulk-delete', [
                'ids' => $userIds
            ]);

        $response->assertStatus(200);

        foreach ($userIds as $id) {
            $this->assertSoftDeleted('users', ['id' => $id]);
        }
    }
}
