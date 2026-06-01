<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 8);
            $table->string('name');
            $table->string('position')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->unique(['team_member_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_translations');
    }
};
