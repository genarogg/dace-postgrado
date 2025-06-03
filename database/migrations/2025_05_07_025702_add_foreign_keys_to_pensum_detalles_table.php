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
        Schema::table('pensum_detalles', function (Blueprint $table) {
            $table->foreign(['materia_id'])->references(['id'])->on('materias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['pensum_id'])->references(['id'])->on('pensums')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pensum_detalles', function (Blueprint $table) {
            $table->dropForeign('pensum_detalles_materia_id_foreign');
            $table->dropForeign('pensum_detalles_pensum_id_foreign');
        });
    }
};
