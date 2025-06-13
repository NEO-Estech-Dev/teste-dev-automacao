<?php

namespace Tests\Feature;

use Tests\TestCase;

class VacancyControllerTest extends TestCase
{
    /**
     * A basic feature get vacancies.
     *
     * @return void
     */
    public function test_get_all_vacancies()
    {
        $response = $this->get('/api/vacancies');

        $response->assertStatus(200);
    }

    /**
     * A basic feature get vacancy.
     *
     * @return void
     */
    public function test_get_vacancy()
    {
        $response = $this->get('/api/vacancies/3');

        $response->assertStatus(200);
    }

    /**
     * A basic feature to subscribe in vacancy.
     *
     * @return void
     */
    public function test_subscribe_vacancy()
    {
        $vacancy = [
            "title_vacancy_job" => "Vaga Backend Pleno",
	        "company_name" => "Google"
        ];

        $response = $this->putJson('/api/vacancies/subscribe/1', $vacancy);

        $response->assertStatus(200);
    }

    /**
     * A basic feature to create user.
     *
     * @return void
     */
    public function test_create_vacancy()
    {
        $vacancy = [
            "title_vacancy_job" => "Vaga Backend Pleno",
	        "description_vacancy_job" => "Vaga para desenvolvedor backend pleno. Solicitado 2 salários minimos.",
	        "type_vacancy_job" => 0,
	        "company_name" => "Google"
        ];

        $response = $this->postJson('/api/vacancies/store', $vacancy);

        $response->assertStatus(201);
    }

    /**
     * A basic feature to update vacancy.
     *
     * @return void
     */
    public function test_update_vacancy()
    {
        $vacancy = [
            "title_vacancy_job" => "Vaga Backend Pleno",
	        "description_vacancy_job" => "Vaga para desenvolvedor backend pleno. Solicitado 2 salários minimos.",
	        "type_vacancy_job" => 0,
	        "company_name" => "Amazon"
        ];

        $response = $this->putJson('/api/vacancies/update/8', $vacancy);

        $response->assertStatus(200);
    }

    /**
     * A basic feature to delete vacancy.
     *
     * @return void
     */
    public function test_delete_vacancy()
    {
        $response = $this->delete('/api/vacancy/delete/6');

        $response->assertStatus(200);
    }
}
