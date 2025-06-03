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
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->text('content')->nullable();
            $table->string('layout')->nullable()->default('default');
            $table->string('status')->nullable()->default('draft');
            $table->string('featured_image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index('pages_parent_id_foreign');
            $table->integer('order')->nullable()->default(0);
            $table->unsignedBigInteger('user_id')->nullable()->index('pages_user_id_foreign');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
