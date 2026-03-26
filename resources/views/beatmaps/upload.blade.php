<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Публикация новой карты') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-8">
                
                <!-- Заголовок и описание -->
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Загрузите вашу карту</h3>
                    <p class="text-sm text-gray-500">
                        Мы автоматически извлечем название песни, исполнителя, фоновое изображение и все сложности из вашего архива.
                    </p>
                </div>

                <form action="{{ route('beatmaps.store') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                    @csrf

                    <!-- Зона загрузки -->
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-pink-400 transition-colors group" id="drop-zone">
                        <input type="file" name="osz_file" id="osz_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".osz" required>
                        
                        <div id="file-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 group-hover:text-pink-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="mt-4 text-sm font-medium text-gray-900">Перетащите файл сюда или нажмите для выбора</p>
                            <p class="mt-1 text-xs text-gray-500 uppercase font-bold">Максимальный размер: 100 МБ</p>
                            <p class="mt-1 text-xs text-gray-500 uppercase font-bold">Допустимый формат: osz</p>
                        </div>

                        <!-- Превью выбранного файла (скрыто по умолчанию) -->
                        <div id="file-info" class="hidden">
                            <p class="text-pink-600 font-bold text-lg" id="filename-display"></p>
                            <p class="text-xs text-gray-400 mt-2">Файл выбран и готов к загрузке</p>
                        </div>
                    </div>

                    <!-- Ошибки валидации -->
                    @if ($errors->any())
                        <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Кнопка отправки -->
                    <div class="mt-8">
                        <button type="submit" id="submit-btn" class="w-full flex justify-center items-center py-4 px-6 border border-transparent shadow-sm text-lg font-bold rounded-xl text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-all disabled:opacity-50">
                            {{ __('Опубликовать') }}
                        </button>
                    </div>

                    <!-- Индикатор загрузки (скрыт по умолчанию) -->
                    <div id="loading-spinner" class="hidden mt-4 text-center">
                        <p class="text-sm text-gray-500 italic">Пожалуйста, подождите. Идет обработка файла...</p>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Маленький скрипт для улучшения UX (показ имени файла) -->
    <script>
        const fileInput = document.getElementById('osz_file');
        const filePlaceholder = document.getElementById('file-placeholder');
        const fileInfo = document.getElementById('file-info');
        const filenameDisplay = document.getElementById('filename-display');
        const form = document.getElementById('upload-form');
        const submitBtn = document.getElementById('submit-btn');
        const loadingSpinner = document.getElementById('loading-spinner');

        fileInput.onchange = e => {
            const file = e.target.files[0];
            if (file) {
                filePlaceholder.classList.add('hidden');
                fileInfo.classList.remove('hidden');
                filenameDisplay.innerText = file.name;
            }
        };

        // Блокируем кнопку при отправке
        form.onsubmit = () => {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Загрузка...';
            loadingSpinner.classList.remove('hidden');
        };
    </script>
</x-app-layout>