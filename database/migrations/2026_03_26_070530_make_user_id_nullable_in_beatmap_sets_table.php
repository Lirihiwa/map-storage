<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * ВАЖНО ДЛЯ СОХРАНЕНИЯ КАРТ ПРИ УДАЛЕНИИ ПОЛЬЗОВАТЕЛЯ
	 */
	public function up(): void
	{
		Schema::table('beatmap_sets', function (Blueprint $table) {
			$table->dropForeign(['user_id']);

			$table->foreignId('user_id')->nullable()
				->constrained()
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('beatmap_sets', function (Blueprint $table) {
			//
		});
	}
};
