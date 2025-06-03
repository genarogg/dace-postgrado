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
        Schema::create('operacion_carrera', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('operacion_id')->nullable()->index('operacion_carrera_operaciones');
            $table->unsignedBigInteger('carrera_id')->nullable()->index('operacion_carrera_carreras');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacion_carrera');
    }
};
