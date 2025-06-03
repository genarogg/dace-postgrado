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
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('estudiante_id')->nullable()->index('inscripcions_estudiante_id_foreign');
            $table->unsignedBigInteger('carrera_id')->nullable()->index('inscripcions_carrera_id_foreign');
            $table->unsignedBigInteger('sede_id')->nullable()->index('inscripcions_sede_id_foreign');
            $table->unsignedBigInteger('periodo_id')->nullable()->index('inscripcions_periodo_id_foreign');
            $table->enum('tipo', ['nuevo', 'regular'])->nullable();
            $table->string('estado', 20)->nullable()->default('activa');
            $table->string('seccion', 10)->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('numero_referencia_pago')->nullable();
            $table->decimal('monto_pago', 10)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('comprobante_pago')->nullable();
            $table->boolean('pago_verificado')->nullable()->default(false);
            $table->string('numero_referencia_pago_administrativo')->nullable();
            $table->decimal('monto_pago_administrativo', 10)->nullable();
            $table->date('fecha_pago_administrativo')->nullable();
            $table->string('comprobante_pago_administrativo')->nullable();
            $table->boolean('pago_verificado_administrativo')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};
