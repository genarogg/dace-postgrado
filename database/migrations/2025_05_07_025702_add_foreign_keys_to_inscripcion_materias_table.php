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
        Schema::table('inscripcion_materias', function (Blueprint $table) {
            $table->foreign(['inscripcion_id'])->references(['id'])->on('inscripcions')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['materia_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['profesor_id'])->references(['id'])->on('profesors')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripcion_materias', function (Blueprint $table) {
            $table->dropForeign('inscripcion_materias_inscripcion_id_foreign');
            $table->dropForeign('inscripcion_materias_materia_id_foreign');
            $table->dropForeign('inscripcion_materias_profesor_id_foreign');
        });
    }
};
