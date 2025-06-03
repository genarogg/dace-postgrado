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
        Schema::create('linea_investigaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pensum_id')->nullable()->index('linea_investigaciones_pensums');
            $table->string('nombre')->nullable();
            $table->string('coordinador')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linea_investigaciones');
    }
};
