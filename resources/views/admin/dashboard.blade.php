<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Статистика') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<!-- Фильтр по времени -->
			<div class="mb-6 flex justify-end">
				<form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
					<span class="text-sm text-gray-600">Статистика за последние:</span>
					<select name="days" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
						<option value="7" {{ $days == 7 ? 'selected' : '' }}>7 дней</option>
						<option value="30" {{ $days == 30 ? 'selected' : '' }}>30 дней</option>
						<option value="90" {{ $days == 90 ? 'selected' : '' }}>90 дней</option>
					</select>
				</form>
			</div>

			<!-- Карточки статистики -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
				<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
					<p class="text-sm text-gray-500 uppercase font-bold">Новых пользователей</p>
					<p class="text-3xl font-black text-pink-600">+{{ $newUsers }}</p>
					<p class="text-xs text-gray-400 mt-1 italic">за {{ $days }} дн.</p>
				</div>
				<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
					<p class="text-sm text-gray-500 uppercase font-bold">Новых карт</p>
					<p class="text-3xl font-black text-blue-600">+{{ $newBeatmapSets }}</p>
					<p class="text-xs text-gray-400 mt-1 italic">за {{ $days }} дн.</p>
				</div>
				<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
					<p class="text-sm text-gray-500 uppercase font-bold">Всего пользователей</p>
					<p class="text-3xl font-black text-gray-800">{{ $totalUsers }}</p>
				</div>
				<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
					<p class="text-sm text-gray-500 uppercase font-bold">Всего карт</p>
					<p class="text-3xl font-black text-gray-800">{{ $totalBeatmapSets }}</p>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
				<!-- Последние пользователи -->
				<div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
					<div class="p-4 bg-gray-50 border-b border-gray-100 font-bold">Последние регистрации</div>
					<table class="w-full text-left text-sm">
						@foreach($recentUsers as $user)
							<tr class="border-b last:border-0 hover:bg-gray-50 transition">
								<td class="p-3">{{ $user->name }}</td>
								<td class="p-3 text-gray-400">{{ $user->created_at->diffForHumans() }}</td>
							</tr>
						@endforeach
					</table>
				</div>

				<!-- Последние карты -->
				<div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
					<div class="p-4 bg-gray-50 border-b border-gray-100 font-bold">Последние загрузки</div>
					<table class="w-full text-left text-sm">
						@foreach($recentBeatmapSets as $map)
							<tr class="border-b last:border-0 hover:bg-gray-50 transition">
								<td class="p-3 font-bold text-pink-600">
									<a href="{{ route('beatmaps.show', $map) }}" wire:navigate>{{ $map->title }}</a>
								</td>
								<td class="p-3 text-gray-400 text-xs">{{ $map->created_at->format('d.m.Y H:i') }}</td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>