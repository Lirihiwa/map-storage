<x-app-layout>
	<div class="py-12 bg-gray-50/50">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<!-- Блок поиска и фильтров -->
			<div class="mb-10">
				<form action="{{ route('beatmaps.index') }}" method="GET" class="max-w-4xl mx-auto">
					<div class="flex flex-col md:flex-row gap-3 bg-white p-2 sm:rounded-3xl shadow-sm border border-gray-200 focus-within:border-pink-300 transition-all">
						<!-- Поле текстового поиска -->
						<div class="flex-1 flex items-center">
							<div class="pl-4 text-gray-400">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
								</svg>
							</div>
							<input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск карты..."
								class="w-full border-none focus:ring-0 py-3 px-4 text-sm text-gray-700 placeholder-gray-400 bg-transparent">
							<!-- Кнопка поиска -->
							<button type="submit"
								class="bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-2xl font-black text-xs transition-all uppercase tracking-widest shadow-md shadow-pink-100">
								Найти
							</button>
						</div>

						<!-- Разделитель (только для широких экранов) -->
						<div class="hidden md:block w-px h-8 bg-gray-100 self-center"></div>

						<!-- Выбор статуса -->
						<div class="flex items-center px-2">
							<select name="status" onchange="this.form.submit()"
								class="border-none focus:ring-0 text-sm font-bold text-gray-600 bg-transparent cursor-pointer hover:text-pink-600 transition-colors uppercase tracking-wider">
								<option value="">Все статусы</option>
								<option value="ranked" {{ request('status') == 'ranked' ? 'selected' : '' }}>Ranked
								</option>
								<option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>
									Qualified</option>
								<option value="loved" {{ request('status') == 'loved' ? 'selected' : '' }}>Loved</option>
								<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
								</option>
								<option value="graveyard" {{ request('status') == 'graveyard' ? 'selected' : '' }}>
									Graveyard</option>
							</select>
						</div>


					</div>

					<!-- Кнопка сброса под формой (если применены фильтры) -->
					@if(request('q') || request('status'))
						<div class="mt-3 text-center">
							<a href="{{ route('beatmaps.index') }}" wire:navigate
								class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-pink-600 transition-colors">
								× Сбросить все фильтры
							</a>
						</div>
					@endif
				</form>
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">
				@foreach ($beatmapSets as $set)
					<!-- Карточка -->
					<div class="group flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md hover:border-pink-300 transition-all duration-300">
						<!-- Верхняя часть: ведет на страницу карты -->
						<a href="{{ route('beatmaps.show', $set) }}" class="flex flex-col flex-1" wire:navigate>
							<!-- Изображение -->
							<div class="h-44 bg-cover bg-center relative shrink-0"
								style="background-image: url('{{ asset('storage/' . ($set->bg_path ?? 'default.jpg')) }}')">
								<span
									class="absolute top-3 right-3 bg-pink-600 text-white text-[10px] font-black px-2 py-1 rounded-md uppercase tracking-wider shadow-sm">
									{{ $set->status }}
								</span>
							</div>

							<!-- Контент (заголовок) -->
							<div
								class="p-5 pb-2 flex-1 text-gray-900 font-bold text-sm leading-tight line-clamp-2 group-hover:text-pink-600 transition-colors">
								{{ $set->artist }} - {{ $set->title }}
							</div>
						</a>

						<!-- Нижняя часть (Футер) -->
						<div class="px-5 pb-5 mt-auto">
							<div class="pt-3 border-t border-gray-100">
								<p class="text-[9px] font-black uppercase tracking-widest leading-tight truncate">
									<!-- Имя маппера из файла -->
									<span class="text-gray-400">Mapped by</span>
									<span class="text-gray-700 ml-0.5">{{ $set->creator }}</span>

									@if($set->user)
										<!-- Точка-разделитель -->
										<span class="mx-1.5 text-gray-300">•</span>

										<!-- Имя загрузившего со ссылкой -->
										<a href="{{ route('users.show', $set->user) }}" wire:navigate
											class="text-pink-500 hover:text-pink-600 hover:underline transition-colors">
											{{ $set->user->name }}
										</a>
									@endif
								</p>
							</div>
						</div>

					</div>
				@endforeach
			</div>

			<div class="mt-12">
				{{ $beatmapSets->links() }}
			</div>
		</div>
	</div>
</x-app-layout>