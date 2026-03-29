<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				{{ $beatmapSet->artist }} - {{ $beatmapSet->title }}
			</h2>
			<a href="{{ route('beatmaps.index') }}" wire:navigate class="text-sm text-pink-600 hover:underline">
				&larr; Назад к списку
			</a>
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

				<div class="flex flex-col md:flex-row">
					<!-- Левая колонка: Визуал, Аудио и Скачивание -->
					<div class="w-full md:w-1/3 p-6 border-b md:border-b-0 md:border-r border-gray-100">
						<div class="rounded-xl overflow-hidden shadow-md mb-6 group relative">
							<img src="{{ asset('storage/' . ($beatmapSet->bg_path ?? 'default.jpg')) }}"
								class="w-full h-auto object-cover transform group-hover:scale-110 transition duration-500"
								alt="Background">
							<div
								class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500">
							</div>
						</div>

						<!-- Кнопка запуска превью трека -->
						@if($beatmapSet->audio_path)
							<button
								onclick="playSong('{{ addslashes($beatmapSet->title) }}', '{{ addslashes($beatmapSet->artist) }}', '{{ asset('storage/' . $beatmapSet->bg_path) }}', '{{ asset('storage/' . $beatmapSet->audio_path) }}')"
								class="mb-6 w-full flex items-center justify-center gap-3 bg-white border-2 border-pink-500 text-pink-600 font-black py-3 rounded-2xl hover:bg-pink-50 transition-all shadow-sm active:scale-95">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 1024 1024">
									<title>Play-circle-outlined SVG Icon</title>
									<path fill="currentColor" d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448s448-200.6 448-448S759.4 64 512 64m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372s372 166.6 372 372s-166.6 372-372 372"/>
									<path fill="currentColor" d="m719.4 499.1l-296.1-215A15.9 15.9 0 0 0 398 297v430c0 13.1 14.8 20.5 25.3 12.9l296.1-215a15.9 15.9 0 0 0 0-25.8m-257.6 134V390.9L628.5 512z"/>
								</svg>
								Слушать
							</button>
						@endif

						<div class="space-y-3">
							<a href="{{ route('beatmaps.download', $beatmapSet) }}" wire:navigate
								class="flex items-center justify-center gap-2 w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-pink-200">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
								</svg>
								Скачать
							</a>
						</div>

						@can('delete', $beatmapSet)
							<div class="mt-4 pt-4 border-t border-gray-100">
								<form action="{{ route('beatmaps.destroy', $beatmapSet) }}" method="POST"
									onsubmit="return confirm('Вы уверены? Карта и её файлы будут удалены навсегда!');">
									@csrf
									@method('DELETE')

									<button type="submit"
										class="w-full text-center text-red-500 hover:text-red-700 text-xs font-bold uppercase tracking-widest transition">
										Удалить
									</button>
								</form>
							</div>
						@endcan
					</div>

					<!-- Правая колонка: Информация и Сложности -->
					<div class="w-full md:w-2/3 p-8">
						<div class="mb-8">
							<h1 class="text-4xl font-black text-gray-900 leading-tight">{{ $beatmapSet->title }}</h1>
							<p class="text-xl text-gray-500 mb-4">{{ $beatmapSet->artist }}</p>

							<div class="flex flex-wrap gap-2 mb-6">
								<span
									class="px-3 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-full uppercase">
									{{ $beatmapSet->status }}
								</span>
								<span
									class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full uppercase">
									BPM: {{ $beatmapSet->bpm }}
								</span>
							</div>
						</div>

						<div class="grid grid-cols-2 gap-y-4 text-sm mb-10 border-t border-b border-gray-50 py-6">
							<div>
								<p class="text-gray-400">Создатель (Mapper)</p>
								<p class="font-bold text-gray-800">{{ $beatmapSet->creator }}</p>
							</div>
							<div>
								<p class="text-gray-400">Источник</p>
								<p class="font-bold text-gray-800">{{ $beatmapSet->source ?? 'Нет данных' }}</p>
							</div>
							<div class="col-span-2">
								<p class="text-gray-400 mb-1">Теги</p>
								<p class="text-gray-600 text-xs italic">{{ $beatmapSet->tags ?? 'Теги отсутствуют' }}
								</p>
							</div>
						</div>

						<h3 class="text-xl font-bold text-gray-900 mb-6">Список сложностей</h3>
						<div class="space-y-3">
							@foreach($beatmapSet->beatmaps as $diff)
								<div
									class="group bg-gray-50 border border-gray-100 p-4 rounded-xl transition flex justify-between items-center shadow-sm hover:shadow-md">
									<div class="flex items-center gap-4">
										<!-- Иконка мода (зависит от mode) -->
										<div
											class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-xs font-black text-gray-400 border border-gray-100">
											@php
												$modes = [0 => 'STD', 1 => 'TKO', 2 => 'CTB', 3 => 'MAN'];
											@endphp
											{{ $modes[$diff->mode] ?? '?' }}
										</div>
										<div>
											<p class="font-bold text-gray-800">{{ $diff->difficulty_name }}</p>
											<div
												class="flex gap-4 text-[10px] uppercase font-bold tracking-widest text-gray-400">
												<span>CS <b class="text-gray-600">{{ $diff->cs }}</b></span>
												<span>AR <b class="text-gray-600">{{ $diff->ar }}</b></span>
												<span>OD <b class="text-gray-600">{{ $diff->od }}</b></span>
												<span>HP <b class="text-gray-600">{{ $diff->hp }}</b></span>
											</div>
										</div>
									</div>
									<div class="text-right">
										@if($diff->star_rating > 0)
											<span
												class="text-lg font-black text-pink-500">{{ number_format($diff->star_rating, 2) }}★</span>
										@else
											<span class="text-xs text-gray-300">No Rating</span>
										@endif
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>