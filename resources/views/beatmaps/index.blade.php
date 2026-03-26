<x-app-layout>
	<div class="py-12 bg-gray-50/50">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">
				@foreach ($beatmapSets as $set)
					<!-- Карточка теперь DIV -->
					<div
						class="group flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md hover:border-pink-300 transition-all duration-300">

						<!-- Верхняя часть: ведет на страницу карты -->
						<a href="{{ route('beatmaps.show', $set) }}" class="flex flex-col flex-1">
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
										<a href="{{ route('users.show', $set->user) }}"
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