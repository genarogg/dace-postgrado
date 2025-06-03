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
        Schema::table('preinscripcions', function (Blueprint $table) {
            $table->foreign(['carrera_id'])->references(['id'])->on('carreras')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estudiante_id'])->references(['id'])->on('estudiantes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sede_id'])->references(['id'])->on('sedes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preinscripcions', function (Blueprint $table) {
            $table->dropForeign('preinscripcions_carrera_id_foreign');
            $table->dropForeign('preinscripcions_estudiante_id_foreign');
            $table->dropForeign('preinscripcions_sede_id_foreign');
        });
    }
};
