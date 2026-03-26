<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function show(User $user)
	{
		$beatmapSets = $user->beatmapSets()
			->with('beatmaps')
			->latest()
			->paginate(12);

		return view('users.show', compact('user', 'beatmapSets'));
	}
}
