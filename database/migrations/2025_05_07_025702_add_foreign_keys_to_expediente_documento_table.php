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
        Schema::table('expediente_documento', function (Blueprint $table) {
            $table->foreign(['documento_requerido_id'])->references(['id'])->on('documento_requeridos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['expediente_id'])->references(['id'])->on('expedientes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expediente_documento', function (Blueprint $table) {
            $table->dropForeign('expediente_documento_documento_requerido_id_foreign');
            $table->dropForeign('expediente_documento_expediente_id_foreign');
        });
    }
};
