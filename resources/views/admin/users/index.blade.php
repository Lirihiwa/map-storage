<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Управление пользователями') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Уведомления об успехе или ошибке -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    @foreach($errors->all() as $error)
                        <span class="block sm:inline">{{ $error }}</span><br>
                    @endforeach
                </div>
            @endif

            <!-- Таблица пользователей -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-bold">
                            <tr>
                                <th class="p-4 w-16">ID</th>
                                <th class="p-4">Пользователь</th>
                                <th class="p-4">Зарегистрирован</th>
                                <th class="p-4">Роли</th>
                                <th class="p-4 text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 text-gray-500 text-sm">#{{ $user->id }}</td>
                                    
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    
                                    <td class="p-4 text-sm text-gray-600">
                                        {{ $user->created_at->format('d.m.Y H:i') }}
                                        <div class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <!-- Форма изменения ролей (чекбоксы) -->
                                        <form action="{{ route('admin.users.roles', $user) }}" method="POST" class="flex flex-wrap items-center gap-3">
                                            @csrf
                                            @method('PATCH')
                                            
                                            @foreach($roles as $role)
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <!-- Чекбокс: если у юзера есть эта роль (содержится в коллекции), он будет checked -->
                                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                                           onchange="this.form.submit()"
                                                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                                           {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}
                                                           
                                                           {{-- Запрещаем админу снимать галочку 'admin' с самого себя --}}
                                                           {{ ($user->id === auth()->id() && $role->name === 'admin') ? 'disabled' : '' }}>
                                                           
                                                    <span class="ml-2 text-sm text-gray-700 
                                                        {{ $role->name === 'admin' ? 'font-bold text-red-600' : 
                                                          ($role->name === 'moderator' ? 'font-bold text-blue-600' : '') }}">
                                                        {{ $role->display_name ?? ucfirst($role->name) }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </form>
                                    </td>
                                    
                                    <td class="p-4 align-middle">
                                        <div class="flex justify-end items-center h-full">
                                            <!-- Кнопка удаления (не показываем для самого себя) -->
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0 flex items-center" 
                                                      onsubmit="return confirm('ВНИМАНИЕ! Вы уверены? Будет удален аккаунт и ВСЕ загруженные им карты навсегда!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition h-[34px] flex items-center justify-center" title="Удалить пользователя">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic px-2">Это вы</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Пагинация -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>