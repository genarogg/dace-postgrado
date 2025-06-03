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
        Schema::table('operacion_sede', function (Blueprint $table) {
            $table->foreign(['operacion_id'], 'operacion_sede_operaciones')->references(['id'])->on('operaciones')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sede_id'], 'operacion_sede_sedes')->references(['id'])->on('sedes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operacion_sede', function (Blueprint $table) {
            $table->dropForeign('operacion_sede_operaciones');
            $table->dropForeign('operacion_sede_sedes');
        });
    }
};
