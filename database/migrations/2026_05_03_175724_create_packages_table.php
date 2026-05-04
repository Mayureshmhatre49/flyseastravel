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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location');
            $table->string('country')->default('India');
            $table->enum('category', ['honeymoon', 'group', 'family', 'adventure', 'international', 'college'])->default('group');
            $table->enum('badge', ['bestseller', 'new', 'limited', 'none'])->default('none');
            $table->integer('days');
            $table->integer('nights');
            $table->enum('tier', ['standard', 'premium'])->default('standard');
            $table->integer('price_per_person');
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->integer('review_count')->default(0);
            $table->text('description')->nullable();
            $table->text('overview')->nullable();
            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('includes_icons')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->integer('seats_left')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
