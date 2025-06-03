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
        Schema::create('materia_electivas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pensum_id')->nullable()->index('materia_electivas_pensums');
            $table->string('nombre')->nullable();
            $table->string('codigo')->nullable();
            $table->integer('creditos')->nullable();
            $table->integer('horas_teoricas')->nullable();
            $table->integer('horas_practicas')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_electivas');
    }
};
