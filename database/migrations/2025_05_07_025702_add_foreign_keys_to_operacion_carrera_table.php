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
        Schema::table('operacion_carrera', function (Blueprint $table) {
            $table->foreign(['carrera_id'], 'operacion_carrera_carreras')->references(['id'])->on('carreras')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['operacion_id'], 'operacion_carrera_operaciones')->references(['id'])->on('operaciones')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operacion_carrera', function (Blueprint $table) {
            $table->dropForeign('operacion_carrera_carreras');
            $table->dropForeign('operacion_carrera_operaciones');
        });
    }
};
