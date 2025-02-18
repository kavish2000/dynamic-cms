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
            $table->id()->autoIncrement();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('cascade');
            $table->string('slug',50);
            $table->string('title',100);
            $table->longText('content');
            $table->timestamps();
            $table->index(['parent_id', 'slug']);
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
