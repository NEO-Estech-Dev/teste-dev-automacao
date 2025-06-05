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
        Schema::create('tb_usuarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idUser')->unique();
            $table->string('name');
            $table->bigInteger('phone');
            $table->integer('genero');
            $table->integer('idFomacao');
            $table->string('nameCurso');
            $table->string('cv');
            $table->string('estado',3);
            $table->string('cidade',55);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_usuarios');
    }
};
