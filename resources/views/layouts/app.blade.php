<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

	<!-- Scripts -->
	@livewireStyles
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
	<div class="min-h-screen bg-gray-100">
		@include('layouts.navigation')

		<!-- Page Heading -->
		@isset($header)
			<header class="bg-white shadow">
				<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
					{{ $header }}
				</div>
			</header>
		@endisset

		<!-- Page Content -->
		<main>
			{{ $slot }}
		</main>
	</div>

	<!-- Глобальный плеер (Persistent) -->
	<div class="fixed bottom-0 left-0 w-full z-50">
		@persist('player')
		<!-- Кнопка развертывания (плавающая) -->
		<div id="player-minimized-control"
			class="fixed bottom-6 right-6 transition-all duration-500 translate-y-24 opacity-0 z-50">
			<button onclick="window.toggleMinimize()"
				class="group flex items-center gap-3 bg-white p-2 pr-4 rounded-full shadow-2xl border border-pink-100 hover:border-pink-200 transition-all active:scale-95">
				<div
					class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-pink-200 group-hover:bg-pink-700">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" fill="none"
						viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
					</svg>
				</div>
				<div class="text-left">
					<p class="text-[9px] font-black uppercase tracking-widest text-pink-600">Играет</p>
					<p id="mini-player-title" class="text-xs font-bold text-gray-700 max-w-[100px] truncate">...</p>
				</div>
			</button>
		</div>

		<!-- Основной плеер -->
		<div id="global-player"
			class="bg-white/95 backdrop-blur-xl border-t border-gray-200 shadow-[0_-10px_30px_rgba(0,0,0,0.08)] transition-all duration-500 translate-y-full opacity-0">
			<audio id="main-audio"></audio>

			<div class="max-w-7xl mx-auto px-4 py-4">
				<div class="flex flex-col gap-4">

					<!-- ВЕРХНИЙ РЯД -->
					<div class="grid grid-cols-3 items-center">

						<!-- Инфо (Лево) -->
						<div class="flex items-center gap-3 overflow-hidden">
							<div class="relative shrink-0">
								<img id="player-bg" src=""
									class="w-14 h-14 rounded-xl object-cover shadow-sm bg-gray-100 border border-gray-100">
								<div class="absolute inset-0 rounded-xl shadow-inner pointer-events-none"></div>
							</div>
							<div class="truncate">
								<h4 id="player-title" class="text-base font-black text-gray-900 truncate leading-tight">
									Song Title</h4>
								<p id="player-artist"
									class="text-[11px] text-gray-400 truncate uppercase font-bold tracking-widest mt-1">
									Artist</p>
							</div>
						</div>

						<!-- Управление -->
						<div class="flex justify-center">
							<button onclick="togglePlay()"
								class="group w-14 h-14 flex items-center justify-center text-pink-600 bg-pink-50 hover:bg-pink-100 rounded-full transition-all active:scale-90 shadow-sm border border-pink-100">
								<svg xmlns="http://www.w3.org/2000/svg" id="play-icon" class="w-10 h-10"
									viewBox="0 0 1024 1024">
									<path fill="currentColor"
										d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448s448-200.6 448-448S759.4 64 512 64m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372s372 166.6 372 372s-166.6 372-372 372" />
									<path fill="currentColor"
										d="m719.4 499.1l-296.1-215A15.9 15.9 0 0 0 398 297v430c0 13.1 14.8 20.5 25.3 12.9l296.1-215a15.9 15.9 0 0 0 0-25.8m-257.6 134V390.9L628.5 512z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" id="pause-icon" class="w-10 h-10 hidden"
									fill="currentColor" viewBox="0 0 1024 1024">
									<path fill="currentColor"
										d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448s448-200.6 448-448S759.4 64 512 64m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372s372 166.6 372 372s-166.6 372-372 372m-88-532h-48c-4.4 0-8 3.6-8 8v304c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V360c0-4.4-3.6-8-8-8m224 0h-48c-4.4 0-8 3.6-8 8v304c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V360c0-4.4-3.6-8-8-8" />
								</svg>
							</button>
						</div>

						<div class="flex items-center justify-end gap-3">
							<!-- Громкость (только десктоп) -->
							<div class="hidden lg:flex items-center gap-2 group mr-4">
								<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
									viewBox="0 0 24 24">
									<path
										d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
									</path>
								</svg>
								<input type="range" min="0" max="1" step="0.1" value="1"
									oninput="changeVolume(this.value)"
									class="w-20 h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-pink-500">
							</div>

							<!-- Кнопка Свернуть -->
							<button onclick="window.toggleMinimize()"
								class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors"
								title="Свернуть">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M19 9l-7 7-7-7" />
								</svg>
							</button>

							<!-- Кнопка Закрыть -->
							<button onclick="window.closePlayer()"
								class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors"
								title="Закрыть">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
									stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M6 18L18 6M6 6l12 12"></path>
								</svg>
							</button>
						</div>
					</div>

					<!-- Нижний ряд: Прогресс -->
					<div class="flex items-center gap-4 px-1">
						<span id="current-time"
							class="text-[11px] font-bold font-mono text-gray-400 w-10 text-left">0:00</span>

						<div class="relative flex-1 h-1.5 group cursor-pointer bg-gray-100 rounded-full"
							id="progress-container" onmousedown="window.startDragging(event)"
							ontouchstart="window.startDragging(event)">

							<div id="progress-bar"
								class="absolute top-0 left-0 h-full bg-pink-500 rounded-full w-0 shadow-[0_0_10px_rgba(236,72,153,0.3)]">
							</div>

						</div>

						<span id="total-duration"
							class="text-[11px] font-bold font-mono text-gray-400 w-10 text-right">0:00</span>
					</div>

				</div>
			</div>
		</div>

		<script>
			if (typeof window.playerInitialized === 'undefined') {
				window.playerInitialized = true;
				window.isMinimized = false;
				window.isDragging = false;

				const getAudio = () => document.getElementById('main-audio');
				const getProgressContainer = () => document.getElementById('progress-container');
				const getProgressBar = () => document.getElementById('progress-bar');
				const getCurrentTimeEl = () => document.getElementById('current-time');

				const getClientX = (e) => {
					if (e.touches && e.touches.length > 0) return e.touches[0].clientX;
					if (e.changedTouches && e.changedTouches.length > 0) return e.changedTouches[0].clientX;
					return e.clientX;
				};

				const performSeek = (e) => {
					const container = getProgressContainer();
					const audio = getAudio();
					if (!container || !audio.duration) return;

					const rect = container.getBoundingClientRect();
					const clientX = getClientX(e);
					let x = clientX - rect.left;
					x = Math.max(0, Math.min(x, rect.width));

					const percent = x / rect.width;
					audio.currentTime = percent * audio.duration;
				};

				const updateVisuals = (e) => {
					const container = getProgressContainer();
					const audio = getAudio();
					if (!container || !audio.duration) return;

					const rect = container.getBoundingClientRect();
					const clientX = getClientX(e);
					let x = clientX - rect.left;
					x = Math.max(0, Math.min(x, rect.width));

					const percent = (x / rect.width) * 100;

					const bar = getProgressBar();
					bar.style.transition = 'none';

					bar.style.width = percent + "%";

					const seekTime = (x / rect.width) * audio.duration;
					getCurrentTimeEl().innerText = window.formatTime(seekTime);
				};

				window.startDragging = function (e) {
					window.isDragging = true;
					updateVisuals(e);
					document.body.style.userSelect = 'none';
				};

				const endDragging = (e) => {
					if (window.isDragging) {
						window.isDragging = false;
						document.body.style.userSelect = '';
						performSeek(e);

						getProgressBar().style.transition = 'width 0.2s linear';
					}
				};

				window.addEventListener('mousemove', (e) => { if (window.isDragging) updateVisuals(e); });
				window.addEventListener('touchmove', (e) => {
					if (window.isDragging) {
						updateVisuals(e);
						if (e.cancelable) e.preventDefault();
					}
				}, { passive: false });

				window.addEventListener('mouseup', endDragging);
				window.addEventListener('touchend', endDragging);

				window.playSong = function (title, artist, bg, audioSrc) {
					const audio = getAudio();
					document.getElementById('player-title').innerText = title;
					document.getElementById('mini-player-title').innerText = title;
					document.getElementById('player-artist').innerText = artist;
					document.getElementById('player-bg').src = bg;
					audio.src = audioSrc;
					audio.play();
					document.getElementById('global-player').classList.remove('translate-y-full', 'opacity-0');
					if (window.isMinimized) window.toggleMinimize();
					window.updatePlayInterface(true);
				};

				window.togglePlay = function () {
					const audio = getAudio();
					audio.paused ? audio.play() : audio.pause();
					window.updatePlayInterface(!audio.paused);
				};

				window.updatePlayInterface = (isPlaying) => {
					document.getElementById('play-icon').classList.toggle('hidden', isPlaying);
					document.getElementById('pause-icon').classList.toggle('hidden', !isPlaying);
				};

				window.formatTime = (s) => {
					if (isNaN(s)) return "0:00";
					const min = Math.floor(s / 60);
					const sec = Math.floor(s % 60);
					return min + ":" + (sec < 10 ? "0" + sec : sec);
				};

				window.toggleMinimize = function () {
					const player = document.getElementById('global-player');
					const miniControl = document.getElementById('player-minimized-control');
					window.isMinimized = !window.isMinimized;
					if (window.isMinimized) {
						player.classList.add('translate-y-full', 'opacity-0');
						miniControl.classList.remove('translate-y-24', 'opacity-0');
					} else {
						player.classList.remove('translate-y-full', 'opacity-0');
						miniControl.classList.add('translate-y-24', 'opacity-0');
					}
				};

				window.closePlayer = function () {
					getAudio().pause();
					document.getElementById('global-player').classList.add('translate-y-full', 'opacity-0');
					document.getElementById('player-minimized-control').classList.add('translate-y-24', 'opacity-0');
					window.isMinimized = false;
				};

				window.changeVolume = (val) => { getAudio().volume = val; };

				document.addEventListener('livewire:init', () => {
					const audio = getAudio();
					audio.ontimeupdate = () => {
						if (window.isDragging || !audio.duration) return;
						const percent = (audio.currentTime / audio.duration) * 100;

						const bar = getProgressBar();
						bar.style.transition = 'width 0.2s linear';
						bar.style.width = percent + "%";
						getCurrentTimeEl().innerText = window.formatTime(audio.currentTime);
					};
					audio.onloadedmetadata = () => {
						document.getElementById('total-duration').innerText = window.formatTime(audio.duration);
					};
				});
			}
		</script>
		@endpersist
	</div>

	@livewireScripts
</body>

</html>