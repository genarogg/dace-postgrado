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
        Schema::table('materia_electivas', function (Blueprint $table) {
            $table->foreign(['pensum_id'], 'materia_electivas_pensums')->references(['id'])->on('pensums')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materia_electivas', function (Blueprint $table) {
            $table->dropForeign('materia_electivas_pensums');
        });
    }
};
