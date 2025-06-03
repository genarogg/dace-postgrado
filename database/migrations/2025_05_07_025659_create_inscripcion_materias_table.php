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
        Schema::create('inscripcion_materias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscripcion_id')->nullable()->index('inscripcion_materias_inscripcion_id_foreign');
            $table->unsignedBigInteger('materia_id')->nullable()->index('inscripcion_materias_materia_id_foreign');
            $table->unsignedBigInteger('profesor_id')->nullable()->index('inscripcion_materias_profesor_id_foreign');
            $table->integer('periodo')->nullable();
            $table->enum('estado', ['inscrita', 'aprobada', 'reprobada', 'retirada'])->nullable()->default('inscrita');
            $table->decimal('nota', 4)->nullable();
            $table->text('observacion_nota')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_materias');
    }
};
