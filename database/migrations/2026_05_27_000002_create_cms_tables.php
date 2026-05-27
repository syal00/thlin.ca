<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->date('published_at')->nullable();
            $table->string('location')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();
            $table->date('posted_at')->nullable();
            $table->date('closes_at')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->longText('bio')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('board_members');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('news_posts');
    }
};
