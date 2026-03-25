<x-app-layout>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
				@foreach ($beatmapSets as $set)
					<!-- Карточка -->
                    <a href="{{ route('beatmaps.show', $set) }}" class="group">
                        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 group-hover:border-pink-300 transition duration-300 shadow-sm group-hover:shadow-md">
                            <!-- Изображение -->
                            <div class="h-40 bg-cover bg-center relative" style="background-image: url('{{ asset('storage/' . $set->bg_path) }}')">
                                <span class="absolute top-2 right-2 bg-pink-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $set->status }}
                                </span>
                            </div>
                            
                            <!-- Контент карточки -->
                            <div class="p-4 bg-gray-50 border-t border-gray-100">
                                <h3 class="text-gray-900 font-bold truncate text-sm" title="{{ $set->title }}">
                                    {{ $set->artist }} - {{ $set->title }}
                                </h3>
                                <p class="text-gray-500 text-xs mt-1 truncate">
                                    {{ $set->creator }}
                                </p>
                            </div>
                        </div>
                    </a>
				@endforeach
			</div>

			<div class="mt-8">
				{{ $beatmapSets->links() }}
			</div>
		</div>
	</div>
</x-app-layout>