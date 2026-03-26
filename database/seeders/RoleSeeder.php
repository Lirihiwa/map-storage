<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$adminRole = \App\Models\Role::create([
			'name' => 'admin',
			'display_name' => 'Администратор',
		]);

		$moderatorRole = \App\Models\Role::create([
			'name' => 'moderator',
			'display_name' => 'Модератор',
		]);

		$firstUser = \App\Models\User::first();
		if ($firstUser) {
			$firstUser->roles()->attach($adminRole);
		}
	}
}
