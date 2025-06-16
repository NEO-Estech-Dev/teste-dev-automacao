<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VacancyControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $recruiter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recruiter = User::factory()->create([
            'email' => 'recruiter@test.com',
            'password' => bcrypt('password'),
            'type' => 'recruiter'
        ]);
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
        $response = $this->actingAs($this->recruiter)
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

        $response = $this->actingAs($this->recruiter)
            ->postJson('/api/vacancy/create', $vacancyData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Vacancy created successfully',
                'vacancy' => $vacancyData
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

        $response = $this->actingAs($this->recruiter)
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
                ]
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

        $response = $this->actingAs($this->recruiter)
            ->deleteJson('/api/vacancy/delete/' . $vacancy->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('vacancies', ['id' => $vacancy->id]);
    }

    public function testBulkDeleteVacanciesDeletesSuccessfully()
    {
        $vacancies = Vacancy::factory()->count(3)->create([
            'recruiter_id' => $this->recruiter->id
        ]);
        $vacancyIds = $vacancies->pluck('id')->toArray();

        $response = $this->actingAs($this->recruiter)
            ->deleteJson('/api/vacancy/bulk-delete', [
                'ids' => $vacancyIds
            ]);

        $response->assertStatus(204);

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

        $response = $this->actingAs($this->recruiter)
            ->putJson('/api/vacancy/change-status/' . $vacancy->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Vacancy status changed successfully'
            ]);

        $this->assertDatabaseHas('vacancies', [
            'id' => $vacancy->id,
            'status' => 'inactive'
        ]);
    }
}
