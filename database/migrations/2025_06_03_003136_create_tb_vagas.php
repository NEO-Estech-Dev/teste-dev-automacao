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
        // Schema::create('tb_vagas', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('titulo');                     
        //     $table->text('descricao');                    
        //     $table->string('tipo_contrato');              
        //     $table->string('local');                      
        //     $table->decimal('salario', 10, 2)->nullable(); 
        //     $table->text('requisitos')->nullable();       
        //     $table->text('beneficios')->nullable();       
        //     $table->unsignedBigInteger('empresa_id')->nullable(); 
        //     $table->date('fechamento_vaga')->nullable();
        //    
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_vagas');
    }
};
