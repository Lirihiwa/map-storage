<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Профиль пользователя') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Шапка профиля -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-8 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <!-- Аватар -->
                    <div class="shrink-0">
                        <div style="width: 128px; height: 128px;" class="rounded-full border-4 border-pink-500 shadow-lg overflow-hidden bg-gray-100">
                            <img src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://www.gravatar.com/avatar/000?d=mp' }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;" 
                                 alt="{{ $user->name }}">
                        </div>
                    </div>

                    <!-- Информация -->
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-4xl font-black text-gray-900 mb-2">{{ $user->name }}</h1>
                        <p class="text-gray-500 mb-4">На сайте с {{ $user->created_at->format('d.m.Y') }}</p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <span class="px-4 py-1 bg-pink-100 text-pink-700 text-sm font-bold rounded-full">
                                Карты: {{ $user->beatmap_sets_count ?? $user->beatmapSets()->count() }}
                            </span>
                            @foreach($user->roles as $role)
                                <span class="px-4 py-1 bg-gray-800 text-white text-sm font-bold rounded-full uppercase tracking-tighter">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Список опубликованных карт -->
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Опубликованные карты</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($beatmapSets as $set)
                    <a href="{{ route('beatmaps.show', $set) }}" wire:navigate class="group">
                        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 group-hover:border-pink-300 transition duration-300 shadow-sm group-hover:shadow-md">
                            <div class="h-40 bg-cover bg-center relative" style="background-image: url('{{ asset('storage/' . $set->bg_path) }}')">
                                <span class="absolute top-2 right-2 bg-pink-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $set->status }}
                                </span>
                            </div>
                            <div class="p-4 bg-gray-50 border-t border-gray-100">
                                <h3 class="text-gray-900 font-bold truncate text-sm" title="{{ $set->title }}">
                                    {{ $set->artist }} - {{ $set->title }}
                                </h3>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full p-12 bg-white rounded-xl border border-dashed border-gray-300 text-center text-gray-500">
                        Этот пользователь еще не опубликовал ни одной карты.
                    </div>
                @endforelse
            </div>

            <!-- Пагинация -->
            <div class="mt-8">
                {{ $beatmapSets->links() }}
            </div>

        </div>
    </div>
</x-app-layout>