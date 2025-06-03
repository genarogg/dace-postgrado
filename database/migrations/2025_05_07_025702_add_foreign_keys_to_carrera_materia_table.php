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
        Schema::table('carrera_materia', function (Blueprint $table) {
            $table->foreign(['carrera_id'])->references(['id'])->on('carreras')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['materia_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrera_materia', function (Blueprint $table) {
            $table->dropForeign('carrera_materia_carrera_id_foreign');
            $table->dropForeign('carrera_materia_materia_id_foreign');
        });
    }
};
