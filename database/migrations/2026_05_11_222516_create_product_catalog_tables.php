<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('slug');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['product_category_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('slug');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_categories');
    }
};
