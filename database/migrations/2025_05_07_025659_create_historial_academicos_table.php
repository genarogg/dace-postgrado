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
        Schema::create('historial_academicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('estudiante_id')->nullable();
            $table->unsignedBigInteger('carrera_id')->nullable()->index('historial_academicos_carrera_id_foreign');
            $table->unsignedBigInteger('sede_id')->nullable()->index('historial_academicos_sede_id_foreign');
            $table->unsignedBigInteger('periodo_ingreso_id')->nullable()->index('historial_academicos_periodo_ingreso_id_foreign');
            $table->unsignedBigInteger('periodo_egreso_id')->nullable()->index('historial_academicos_periodo_egreso_id_foreign');
            $table->enum('estado', ['activo', 'egresado', 'retirado', 'suspendido'])->nullable()->default('activo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('pensum_id')->nullable()->index('historial_academicos_pensum_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_academicos');
    }
};
