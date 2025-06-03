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
        Schema::table('historial_academicos', function (Blueprint $table) {
            $table->foreign(['carrera_id'])->references(['id'])->on('carreras')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estudiante_id'])->references(['id'])->on('estudiantes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['pensum_id'])->references(['id'])->on('pensums')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['periodo_egreso_id'])->references(['id'])->on('periodos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['periodo_ingreso_id'])->references(['id'])->on('periodos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sede_id'])->references(['id'])->on('sedes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historial_academicos', function (Blueprint $table) {
            $table->dropForeign('historial_academicos_carrera_id_foreign');
            $table->dropForeign('historial_academicos_estudiante_id_foreign');
            $table->dropForeign('historial_academicos_pensum_id_foreign');
            $table->dropForeign('historial_academicos_periodo_egreso_id_foreign');
            $table->dropForeign('historial_academicos_periodo_ingreso_id_foreign');
            $table->dropForeign('historial_academicos_sede_id_foreign');
        });
    }
};
