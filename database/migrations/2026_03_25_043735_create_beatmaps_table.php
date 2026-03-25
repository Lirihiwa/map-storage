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
			$table->foreignId('beatmap_set_id')->constrained()->onDelete('cascade');
			$table->string('difficulty_name');
			$table->integer('mode')->default(0); // 0: osu!, 1: Taiko, 2: Catch the Beat, 3: osu!mania
			$table->decimal('star_rating', 5, 2)->default(0);
			$table->decimal('ar', 3, 1);
			$table->decimal('od', 3, 1);
			$table->decimal('cs', 3, 1);
			$table->decimal('hp', 3, 1);
			$table->integer('total_length');
            $table->timestamps();
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
