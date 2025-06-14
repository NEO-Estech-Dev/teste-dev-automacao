<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblCreateCandidates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('cpf', 15)->unique()->nullable(false);
            $table->string('phone', 11)->unique()->nullable();
            $table->string('linkedin')->unique()->nullable();
            $table->string('github')->unique()->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->delete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_candidates');
    }
}
