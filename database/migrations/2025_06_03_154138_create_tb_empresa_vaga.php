<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_empresa_vaga', function (Blueprint $table) {
            $table->id();
             $table->foreignId('id_empresa')->constrained('tb_recruiter','idUserRecruiter')->onDelete('cascade');
             $table->foreignId('vaga_id')->constrained('tb_vagas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_empresa_vaga');
    }
};
