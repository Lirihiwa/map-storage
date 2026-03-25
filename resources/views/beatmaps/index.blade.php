<x-app-layout>
	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
				@foreach ($beatmapSets as $set)
					<div class="bg-gray-200 rounded-lg shadow-md overflow-hidden">
						<div class="h-40 bg-cover bg-center relative" style="background-image: url('{{ asset('storage/' . $set->bg_path) }}');">
							<span class="absolute top-2 right-2 bg-pink-600 text-white text-xs px-2 py-1 rounded uppercase">
								{{ $set->status }}
							</span>
						</div>
						<span class="block px-4 pt-2">{{ $set->artist }} - {{ $set->title }}</span>
						<span class="block px-4 text-sm text-gray-600">{{ $set->creator }}</span>
					</div>
				@endforeach
			</div>

			<div class="mt-8">
				{{ $beatmapSets->links() }}
			</div>
		</div>
	</div>
</x-app-layout>