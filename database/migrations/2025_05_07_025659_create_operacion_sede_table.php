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
        Schema::create('operacion_sede', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operacion_id')->index('operacion_sede_operaciones');
            $table->unsignedBigInteger('sede_id')->index('operacion_sede_sedes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacion_sede');
    }
};
