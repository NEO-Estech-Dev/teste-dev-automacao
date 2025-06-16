<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vacancy;
use App\Models\Candidate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $token;
    private $recruiter;
    private $candidate;
    private $vacancy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recruiter = User::factory()->create([
            'email' => 'recruiter@test.com',
            'password' => bcrypt('password'),
            'type' => 'recruiter'
        ]);

        $this->candidate = User::factory()->create([
            'email' => 'candidate@test.com',
            'password' => bcrypt('password'),
            'type' => 'candidate'
        ]);

        $this->vacancy = Vacancy::factory()->create([
            'recruiter_id' => $this->recruiter->id
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'recruiter@test.com',
            'password' => 'password'
        ]);

        $this->token = $response->json('token');
    }

    public function testListCandidatesReturnsCandidates()
    {
        Candidate::factory()->count(3)->create([
            'user_id' => $this->candidate->id,
            'vacancy_id' => $this->vacancy->id
        ]);

        $response = $this->getJson('/api/candidate/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'status',
                        'curriculum_url',
                        'user' => [
                            'id',
                            'name',
                            'email'
                        ],
                        'vacancy' => [
                            'id',
                            'title',
                            'company_name'
                        ]
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

    public function testApplyVacancyRequiresCandidateAccount()
    {
        $response = $this->actingAs($this->recruiter)
            ->postJson('/api/candidate/apply/' . $this->vacancy->id);

        $response->assertStatus(403);
    }

    public function testUpdateCandidateStatusRequiresRecruiterAccount()
    {
        $candidate = Candidate::factory()->create([
            'user_id' => $this->candidate->id,
            'vacancy_id' => $this->vacancy->id
        ]);

        $response = $this->actingAs($this->candidate)
            ->putJson('/api/candidate/update-status/' . $candidate->id, [
                'status' => 'shortlisted'
            ]);

        $response->assertStatus(403);
    }

    public function testDeleteCandidateDeletesSuccessfully()
    {
        $candidate = Candidate::factory()->create([
            'user_id' => $this->candidate->id,
            'vacancy_id' => $this->vacancy->id
        ]);

        $response = $this->actingAs($this->recruiter)
            ->deleteJson('/api/candidate/delete/' . $candidate->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('candidates', ['id' => $candidate->id]);
    }

    public function testBulkDeleteCandidatesDeletesSuccessfully()
    {
        $candidates = Candidate::factory()->count(3)->create([
            'user_id' => $this->candidate->id,
            'vacancy_id' => $this->vacancy->id
        ]);
        $candidateIds = $candidates->pluck('id')->toArray();

        $response = $this->actingAs($this->recruiter)
            ->deleteJson('/api/candidate/bulk-delete', [
                'ids' => $candidateIds
            ]);

        $response->assertStatus(204);

        foreach ($candidateIds as $id) {
            $this->assertSoftDeleted('candidates', ['id' => $id]);
        }
    }

    public function testApplyVacancyAsCandidate()
    {
        $response = $this->actingAs($this->candidate)
            ->postJson('/api/candidate/apply/' . $this->vacancy->id, [
                'curriculum_url' => 'https://example.com/curriculum.pdf'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'candidate'
            ]);
    }

    public function testUpdateCandidateStatusAsRecruiter()
    {
        $candidate = Candidate::factory()->create([
            'user_id' => $this->candidate->id,
            'vacancy_id' => $this->vacancy->id
        ]);

        $response = $this->actingAs($this->recruiter)
            ->putJson('/api/candidate/update-status/' . $candidate->id, [
                'status' => 'shortlisted'
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'candidate'
            ]);
    }

    public function testUpdateCandidateStatusReturnsNotFoundIfCandidateDoesNotExist()
    {
        $nonExistentId = 999999;

        $response = $this->actingAs($this->recruiter)
            ->putJson('/api/candidate/update-status/' . $nonExistentId, [
                'status' => 'shortlisted'
            ]);

        $response->assertStatus(404);
    }

    public function testDeleteCandidateReturnsNotFoundIfCandidateDoesNotExist()
    {
        $nonExistentId = 999999;

        $response = $this->actingAs($this->recruiter)
            ->deleteJson('/api/candidate/' . $nonExistentId);

        $response->assertStatus(404);
    }
}
