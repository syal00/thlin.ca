<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('section')->default('general');
            $table->string('meta_description')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('template')->default('standard');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['section', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
