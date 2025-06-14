<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    /**
     * A basic feature get candidate.
     *
     * @return void
     */
    public function test_get_all_candidate()
    {
        $response = $this->get('/api/candidates');

        $response->assertStatus(200);
    }

    /**
     * A basic feature get candidate
     *
     * @return void
     */
    public function test_get_candidate()
    {
        $response = $this->get('/api/candidates/1');

        $response->assertStatus(200);
    }

    /**
     * A basic feature to create candidate
     *
     * @return void
     */
    public function test_create_candidate()
    {
        $candidate = [
            "cpf" => "123.456.789-00",
            "linkedin" => "linkedin.com/candidato 1",
            "github" => "github.com/candidato 1",
            "phone" => "73912345678",
            "user_id" => 1
        ];

        $response = $this->postJson('/api/candidates/store', $candidate);

        $response->assertStatus(201);
    }

    /**
     * A basic feature to update candidate
     *
     * @return void
     */
    public function test_update_candidate()
    {
        $candidate = [
            "cpf" => "123.456.789-00",
            "linkedin" => "linkedin.com/candidato 1",
            "github" => "github.com/candidato 1",
            "phone" => "73912345678",
            "user_id" => 1
        ];

        $response = $this->putJson('/api/candidates/update/1', $candidate);

        $response->assertStatus(200);
    }

    /**
     * A basic feature to delete candidate
     *
     * @return void
     */
    public function test_delete_candidate()
    {
        $response = $this->delete('/api/candidates/delete/2');

        $response->assertStatus(200);
    }
}
