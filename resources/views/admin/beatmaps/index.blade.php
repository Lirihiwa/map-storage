<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Управление картами (Модерация)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Блок с уведомлениями -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Фильтр по статусам -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('admin.beatmaps.index') }}" wire:navigate
                   class="px-4 py-2 rounded-md text-sm font-medium {{ !request('status') ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Все карты
                </a>
                <a href="{{ route('admin.beatmaps.index', ['status' => 'pending']) }}" wire:navigate
                   class="px-4 py-2 rounded-md text-sm font-medium {{ request('status') == 'pending' ? 'bg-pink-600 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Ожидают проверки (Pending)
                </a>
            </div>

            <!-- Таблица карт -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-bold">
                            <tr>
                                <th class="p-4">Карта</th>
                                <th class="p-4">Загрузил</th>
                                <th class="p-4">Статус</th>
                                <th class="p-4 text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($beatmapSets as $map)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4">
                                        <div class="flex items-center gap-4">
                                            <!-- Миниатюра фона -->
                                            <div class="w-16 h-12 bg-cover bg-center rounded shadow-sm border border-gray-200" 
                                                 style="background-image: url('{{ asset('storage/' . ($map->bg_path ?? 'default.jpg')) }}')">
                                            </div>
                                            <div>
                                                <!-- Ссылка на страницу просмотра карты (откроется в новой вкладке) -->
                                                <a href="{{ route('beatmaps.show', $map) }}" wire:navigate target="_blank" class="font-bold text-gray-900 hover:text-pink-600 transition">
                                                    {{ $map->artist }} - {{ $map->title }}
                                                </a>
												<!-- TODO: Сделать кликабельным (переход в поиск => /panel/beatmaps?mapper=*) -->
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Mapped by <span class="font-semibold">{{ $map->creator }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <!-- Имя пользователя, загрузившего карту (через связь user) -->
										<!-- TODO: Сделать кликабельным (переход на страницу => panel/users/{user}) -->
                                        <div class="text-sm text-gray-900 font-medium">{{ $map->user->name ?? 'Неизвестно' }}</div>
                                        <div class="text-xs text-gray-500">{{ $map->created_at->format('d.m.Y H:i') }}</div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <!-- Текущий статус -->
										<!-- TODO: Сделать кликабельным (переход в поиск => /panel/beatmaps?status=*) -->
                                        <span class="px-2 py-1 text-xs font-bold uppercase rounded-full 
                                            {{ $map->status == 'ranked' ? 'bg-green-100 text-green-700' : 
                                              ($map->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                              ($map->status == 'loved' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-700')) }}">
                                            {{ $map->status }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 align-middle">
										<!-- Обертка для кнопок, чтобы они стояли в ряд по центру -->
										<div class="flex justify-end items-center gap-2 h-full">
											
											<!-- Форма смены статуса -->
											<form action="{{ route('admin.beatmaps.status', $map) }}" method="POST" class="m-0 flex items-center">
												@csrf
												@method('PATCH')
												<select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md py-1.5 pl-3 pr-8 focus:ring-pink-500 focus:border-pink-500 cursor-pointer h-[34px]">
													<option value="pending" {{ $map->status == 'pending' ? 'selected' : '' }}>Pending</option>
													<option value="ranked" {{ $map->status == 'ranked' ? 'selected' : '' }}>Ranked</option>
													<option value="loved" {{ $map->status == 'loved' ? 'selected' : '' }}>Loved</option>
													<option value="qualified" {{ $map->status == 'qualified' ? 'selected' : '' }}>Qualified</option>
													<option value="work in progress" {{ $map->status == 'work in progress' ? 'selected' : '' }}>WIP</option>
													<option value="graveyard" {{ $map->status == 'graveyard' ? 'selected' : '' }}>Graveyard</option>
												</select>
											</form>

											<!-- Кнопка удаления -->
											<form action="{{ route('admin.beatmaps.destroy', $map) }}" method="POST" class="m-0 flex items-center" onsubmit="return confirm('Вы уверены? Карта и её файлы будут удалены навсегда!');">
												@csrf
												@method('DELETE')
												<button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition h-[34px] flex items-center justify-center" title="Удалить карту">
													<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
													</svg>
												</button>
											</form>
										</div>
									</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">
                                        В этой категории пока нет карт.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Пагинация -->
            <div class="mt-4">
                {{ $beatmapSets->links() }}
            </div>

        </div>
    </div>
</x-app-layout>