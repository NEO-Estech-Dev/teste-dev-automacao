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
        Schema::table('tb_usuarios', function (Blueprint $table) {
            $table->integer('estado')->change();
        $table->integer('cidade')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_usuarios', function (Blueprint $table) {
            $table->string('estado', 3)->change();
              $table->string('cidade', 55)->change();
        });
    }
};
