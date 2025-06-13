<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblCreateVacanciesJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vacancies_job', function (Blueprint $table) {
            $table->id();
            $table->string('title_vacancy_job')->nullable(false);
            $table->string('description_vacancy_job')->nullable(false);
            $table->string('company_name')->nullable(false);
            $table->tinyInteger('type_vacancy_job')->nullable(false);
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->timestamps();

            $table->foreign('candidate_id')->references('id')->on('users')->delete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_vacancies_job');
    }
}
