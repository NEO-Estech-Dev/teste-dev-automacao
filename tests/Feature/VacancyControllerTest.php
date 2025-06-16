<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyControllerTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    private $recruiter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recruiter = User::factory()->create([
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

    public function testListVacanciesReturnsVacancies()
    {
        Vacancy::factory()->count(3)->create([
            'recruiter_id' => $this->recruiter->id
        ]);

        $response = $this->getJson('/api/vacancy/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'type',
                        'description',
                        'company_name',
                        'salary',
                        'status',
                        'recruiter' => [
                            'id',
                            'name',
                            'email'
                        ],
                        'created_at',
                        'updated_at'
                    ]
                ],
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }

    public function testCreateVacancyValidatesInput()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/vacancy/create', [
                'title' => '',
                'type' => 'INVALID_TYPE',
                'salary' => 'not-a-number'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'type', 'salary']);
    }

    public function testCreateVacancyCreatesSuccessfully()
    {
        $vacancyData = [
            'title' => 'Test Vacancy',
            'type' => 'CLT',
            'description' => 'Test Description',
            'company_name' => 'Test Company',
            'salary' => 5000
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/vacancy/create', $vacancyData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Vacancy created successfully',
                'vacancy' => $vacancyData,
                'status' => 200
            ]);

        $this->assertDatabaseHas('vacancies', array_merge($vacancyData, [
            'recruiter_id' => $this->recruiter->id,
            'status' => 'active'
        ]));
    }

    public function testUpdateVacancyUpdatesSuccessfully()
    {
        $vacancy = Vacancy::factory()->create([
            'recruiter_id' => $this->recruiter->id
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/vacancy/update/' . $vacancy->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Vacancy updated successfully',
                'vacancy' => [
                    'id' => $vacancy->id,
                    'title' => 'Updated Title',
                    'type' => $vacancy->type->value,
                    'description' => 'Updated Description',
                    'company_name' => $vacancy->company_name,
                    'salary' => $vacancy->salary,
                    'status' => $vacancy->status,
                    'recruiter_id' => $vacancy->recruiter_id,
                    'created_at' => $vacancy->created_at->toJSON(),
                    'updated_at' => $vacancy->updated_at->toJSON()
                ],
                'status' => 200
            ]);

        $this->assertDatabaseHas('vacancies', array_merge($updateData, [
            'id' => $vacancy->id
        ]));
    }

    public function testDeleteVacancyDeletesSuccessfully()
    {
        $vacancy = Vacancy::factory()->create([
            'recruiter_id' => $this->recruiter->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/vacancy/delete/' . $vacancy->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('vacancies', ['id' => $vacancy->id]);
    }

    public function testBulkDeleteVacanciesDeletesSuccessfully()
    {
        $vacancies = Vacancy::factory()->count(3)->create([
            'recruiter_id' => $this->recruiter->id
        ]);
        $vacancyIds = $vacancies->pluck('id')->toArray();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/vacancy/bulk-delete', [
                'ids' => $vacancyIds
            ]);

        $response->assertStatus(200);

        foreach ($vacancyIds as $id) {
            $this->assertSoftDeleted('vacancies', ['id' => $id]);
        }
    }

    public function testChangeVacancyStatusChangesSuccessfully()
    {
        $vacancy = Vacancy::factory()->create([
            'recruiter_id' => $this->recruiter->id,
            'status' => 'active'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/vacancy/change-status/' . $vacancy->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Vacancy status changed successfully',
                'status' => 200
            ]);

        $this->assertDatabaseHas('vacancies', [
            'id' => $vacancy->id,
            'status' => 'inactive'
        ]);
    }
}
