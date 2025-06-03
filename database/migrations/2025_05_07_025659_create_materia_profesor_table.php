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
        Schema::create('materia_profesor', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('materia_id')->nullable();
            $table->unsignedBigInteger('profesor_id')->nullable()->index('materia_profesor_profesor_id_foreign');
            $table->unsignedBigInteger('periodo_id')->nullable()->index('materia_profesor_periodo_id_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['materia_id', 'profesor_id', 'periodo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_profesor');
    }
};
