<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

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
    }

    public function testListUsersReturnsUsers()
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
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
        $response = $this->actingAs($this->user)
            ->putJson('/api/user/update', [
                'email' => 'invalid-email'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function testUpdateUserUpdatesSuccessfully()
    {
        $response = $this->actingAs($this->user)
            ->putJson('/api/user/update', [
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
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com'
        ]);
    }

    public function testDeleteUserDeletesSuccessfully()
    {
        $response = $this->actingAs($this->user)
            ->deleteJson('/api/user/delete');

        $response->assertStatus(204);
        $this->assertSoftDeleted('users', ['id' => $this->user->id]);
    }

    public function testBulkDeleteUsersDeletesSuccessfully()
    {
        $users = User::factory()->count(3)->create();
        $userIds = $users->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->deleteJson('/api/user/bulk-delete', [
                'ids' => $userIds
            ]);

        $response->assertStatus(204);

        foreach ($userIds as $id) {
            $this->assertSoftDeleted('users', ['id' => $id]);
        }
    }
}
