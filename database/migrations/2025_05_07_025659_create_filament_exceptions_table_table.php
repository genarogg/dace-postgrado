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
        Schema::create('filament_exceptions_table', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('code');
            $table->longText('message');
            $table->string('file');
            $table->integer('line');
            $table->text('trace');
            $table->string('method');
            $table->string('path');
            $table->text('query');
            $table->text('body');
            $table->text('cookies');
            $table->text('headers');
            $table->string('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filament_exceptions_table');
    }
};
