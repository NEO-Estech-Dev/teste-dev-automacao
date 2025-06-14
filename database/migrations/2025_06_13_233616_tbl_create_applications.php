<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblCreateApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacancy_id')->nullable(false);
            $table->unsignedBigInteger('candidate_id')->nullable(false);
            $table->timestamps();

            $table->foreign('vacancy_id')->references('id')->on('tbl_vacancies_job')->delete('CASCADE');
            $table->foreign('candidate_id')->references('id')->on('tbl_candidates')->delete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_applications');
    }
}
