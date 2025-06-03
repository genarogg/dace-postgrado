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
        Schema::table('materia_profesor', function (Blueprint $table) {
            $table->foreign(['materia_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['periodo_id'])->references(['id'])->on('periodos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['profesor_id'])->references(['id'])->on('profesors')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materia_profesor', function (Blueprint $table) {
            $table->dropForeign('materia_profesor_materia_id_foreign');
            $table->dropForeign('materia_profesor_periodo_id_foreign');
            $table->dropForeign('materia_profesor_profesor_id_foreign');
        });
    }
};
