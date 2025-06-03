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
        Schema::create('carrera_sede', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('carrera_id')->nullable();
            $table->unsignedBigInteger('sede_id')->nullable()->index('carrera_sede_sede_id_foreign');
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['carrera_id', 'sede_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrera_sede');
    }
};
