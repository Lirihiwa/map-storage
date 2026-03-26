<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function index() {
		$users = User::with('roles')->latest()->paginate(20);
		$roles = Role::all();

		return view('admin.users.index', compact('users','roles'));
	}

	public function updateRoles(Request $request, User $user)
	{
		$roles = $request->input('roles', []);

		if ($user->id === auth()->id() && !in_array('admin', $roles)) {
			$roles[] = 'admin';
			$request->merge(['roles' => $roles]);
		}

		$request->validate([
			'roles' => 'nullable|array',
			'roles.*'=> 'string|exists:roles,name',
		]);

		$user->syncRoles($request->input('roles', []));

		return back()->with('success',"Роли пользователя {$user->name} успешно обновлены");
	}

	public function destroy(User $user)
	{
		if ($user->id === auth()->id()) {
			return back()->withErrors(['error' => 'Нельзя удалить себя']);
		}

		$userName = $user->name;
		$user->delete();

		return back()->with('success', "Пользователь {$userName} удален");
	}
}
