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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 200)->unique();
            $table->json('title');
            $table->json('excerpt')->nullable();
            $table->json('body');
            $table->string('featured_image', 255)->nullable();
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('allow_comments')->default(true);
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
        Schema::dropIfExists('posts');
    }
};
