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
        Schema::create('preinscripcions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('estudiante_id')->nullable()->index('preinscripcions_estudiante_id_foreign');
            $table->unsignedBigInteger('carrera_id')->nullable()->index('preinscripcions_carrera_id_foreign');
            $table->unsignedBigInteger('sede_id')->nullable()->index('preinscripcions_sede_id_foreign');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->nullable()->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('numero_referencia_pago')->nullable();
            $table->decimal('monto_pago', 10)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('comprobante_pago')->nullable();
            $table->boolean('pago_verificado')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preinscripcions');
    }
};
