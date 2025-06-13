<?php

namespace Tests\App\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature get users.
     *
     * @return void
     */
    public function test_get_all_users()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    /**
     * A basic feature get user.
     *
     * @return void
     */
    public function test_get_user()
    {
        $response = $this->get('/api/users/1');

        $response->assertStatus(200);
    }

    /**
     * A basic feature to active user.
     *
     * @return void
     */
    public function test_active_user()
    {
        $response = $this->get('/api/users/active/1');

        $response->assertStatus(200);
    }

    /**
     * A basic feature to create user.
     *
     * @return void
     */
    public function test_create_user()
    {
        $randomValue = rand(0,999);

        $user = [
            'fullname' => 'Usuario ' . $randomValue,
            'level' => rand(0,1),
            'email' => 'usuario'.$randomValue.'@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];

        $response = $this->postJson('/api/users/store', $user);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'usuario'.$randomValue.'@gmail.com'
        ]);
    }

    /**
     * A basic feature to update user.
     *
     * @return void
     */
    public function test_update_user()
    {
        $user = [
            'fullname' => 'Usuário 3',
            'level' => rand(0,1),
            'email' => 'usuario3@gmail.com',
            'password' => '123', // password
        ];

        $response = $this->putJson('/api/users/update/8', $user);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'usuario3@gmail.com'
        ]);
    }

    /**
     * A basic feature to delete user.
     *
     * @return void
     */
    public function test_delete_user()
    {
        $response = $this->delete('/api/users/delete/1');

        $response->assertStatus(200);
    }
}
