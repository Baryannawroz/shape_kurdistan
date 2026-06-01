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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->json('name');
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->json('about_content')->nullable();
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->json('contact_address')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('color_theme', 20)->default('#1E40AF');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
