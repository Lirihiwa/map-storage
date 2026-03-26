<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

	protected $fillable = [
		'name',
		'email',
		'password',
		'avatar_path',
	];

	public function isAdmin(): bool
	{
		return $this->hasRole('admin');
	}

	public function isModerator(): bool
	{
		return $this->hasRole('moderator');
	}

	// Все роли пользователя
	public function roles()
	{
		return $this->belongsToMany(Role::class);
	}

	// Проверяет есть ли у пользователя хотя бы одна роль из списка
	public function hasRole(string|array $roles): bool
	{
		if (is_string($roles)) {
			return $this->roles->contains('name', $roles);
		}

		foreach ($roles as $role) {
			if ($this->hasRole($role)) {
				return true;
			}
		}

		return false;
	}

	// Выдает роль пользователю
	public function assignRole(string $roleName)
	{
		$role = Role::where('name', $roleName)->first();

		if ($role && !$this->hasRole($roleName)) {
			$this->roles()->attach($role);
		}
	}

	// Удаляет роль у пользователя
	public function removeRole(string $roleName) 
	{
		$role = Role::where('name', $roleName)->first();

		if ($role && $this->hasRole($roleName)) {
			$this->roles()->detach($role);
		}
	}

	// Синхронизирует роли пользователя, удаляя старые и добавляя новые
	public function syncRoles(array $roles) 
	{
		$roleIds = Role::whereIn('name', $roles)->pluck('id');
		$this->roles()->sync($roleIds);
	}
}
