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
        Schema::table('carrera_sede', function (Blueprint $table) {
            $table->foreign(['carrera_id'])->references(['id'])->on('carreras')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sede_id'])->references(['id'])->on('sedes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrera_sede', function (Blueprint $table) {
            $table->dropForeign('carrera_sede_carrera_id_foreign');
            $table->dropForeign('carrera_sede_sede_id_foreign');
        });
    }
};
