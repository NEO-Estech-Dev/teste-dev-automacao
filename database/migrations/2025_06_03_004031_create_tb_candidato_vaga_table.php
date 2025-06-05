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
        Schema::create('tb_candidato_vaga', function (Blueprint $table) {
            $table->id();
             $table->foreignId('candidato_id')->constrained('tb_usuarios','idUser')->onDelete('cascade');
             $table->foreignId('vaga_id')->constrained('tb_vagas')->onDelete('cascade');
             $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_candidato_vaga');
    }
};
