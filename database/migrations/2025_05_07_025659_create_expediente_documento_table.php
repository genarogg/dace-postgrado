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
        Schema::create('expediente_documento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('expediente_id');
            $table->unsignedBigInteger('documento_requerido_id')->index('expediente_documento_documento_requerido_id_foreign');
            $table->boolean('entregado')->default(false);
            $table->timestamps();

            $table->unique(['expediente_id', 'documento_requerido_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_documento');
    }
};
