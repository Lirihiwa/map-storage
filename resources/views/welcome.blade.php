<x-app-layout>
    <!-- Часть с призывом к действию -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                Делись <span class="text-pink-600">картами</span> без ограничений
            </h1>
            <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto">
                Открытый сервис публикации и скачивания карт osu!.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('beatmaps.index') }}" wire:navigate class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-lg transition shadow-md">
                    Смотреть карты
                </a>
                @auth
                    <a href="{{ route('beatmaps.upload') }}" wire:navigate class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition border border-gray-300">
                        Загрузить .osz
                    </a>
                @else
                    <a href="{{ route('register') }}" wire:navigate class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition border border-gray-300">
                        Присоединиться
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Статистика -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['beatmap_sets'] }}</div>
                    <div class="text-gray-500 uppercase tracking-widest text-xs mt-1">Карт загружено</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['beatmaps'] }}</div>
                    <div class="text-gray-500 uppercase tracking-widest text-xs mt-1">Сложностей</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['users'] }}</div>
                    <div class="text-gray-500 uppercase tracking-widest text-xs mt-1">Пользователей</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние загруженные карты -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Недавние</h2>
                    <p class="text-gray-500 text-sm mt-1">Самые свежие карты</p>
                </div>
                <a href="{{ route('beatmaps.index') }}" wire:navigate class="text-pink-600 hover:text-pink-700 font-semibold text-sm flex items-center gap-1">
                    Все карты <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($latestMaps as $set)
					<!-- TODO: заменить маршрут на beatmap.show -->
					<!-- Карточка -->
                    <a href="{{ route('beatmaps.show', $set) }}" wire:navigate class="group">
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
        </div>
    </div>
</x-app-layout>