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
        Schema::table('materia_prerrequisito', function (Blueprint $table) {
            $table->foreign(['materia_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['prerrequisito_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materia_prerrequisito', function (Blueprint $table) {
            $table->dropForeign('materia_prerrequisito_materia_id_foreign');
            $table->dropForeign('materia_prerrequisito_prerrequisito_id_foreign');
        });
    }
};
