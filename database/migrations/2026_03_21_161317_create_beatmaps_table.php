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
        Schema::create('beatmaps', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->string('artist');
			$table->string('title');
			$table->string('version');
			$table->string('creator');
			$table->string('source')->nullable();
			$table->string('tags')->nullable();
			$table->integer('bpm')->nullable();
			$table->integer('cs')->nullable();
			$table->integer('od')->nullable();
			$table->integer('hp')->nullable();
			$table->integer('ar')->nullable();
			$table->integer('length')->nullable();
			$table->integer('max_combo')->nullable();
			$table->integer('circles')->nullable();
			$table->integer('sliders')->nullable();
			$table->integer('spinners')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beatmaps');
    }
};
