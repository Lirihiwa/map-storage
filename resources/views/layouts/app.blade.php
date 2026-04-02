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

		<!-- ФУТЕР -->
		<footer class="bg-white border-t border-gray-200 pt-12 pb-32 mt-12">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-12">

					<!-- Левая часть: О проекте -->
					<div class="space-y-4">
						<div class="flex items-center gap-2">
							<x-application-logo class="block h-8 w-auto fill-current text-pink-600" />
							<span class="text-xl font-black tracking-tighter text-gray-900">MAP<span
									class="text-pink-600">STORAGE</span></span>
						</div>
						<p class="text-sm text-gray-500 leading-relaxed">
							Открытая платформа для обмена и публикации карт osu!
						</p>
					</div>

					<!-- Средняя часть: Навигация -->
					<div>
						<h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6">Навигация</h3>
						<ul class="grid grid-cols-2 gap-4 text-sm font-medium text-gray-600">
							<li><a href="{{ route('welcome') }}" wire:navigate
									class="hover:text-pink-600 transition-colors">Главная</a></li>
							<li><a href="{{ route('beatmaps.index') }}" wire:navigate
									class="hover:text-pink-600 transition-colors">Карты</a></li>
							<li><a href="{{ route('beatmaps.upload') }}" wire:navigate
									class="hover:text-pink-600 transition-colors">Публикация</a></li>
							<li><a href="{{ route('profile.edit') }}" wire:navigate
									class="hover:text-pink-600 transition-colors">Профиль</a></li>
						</ul>
					</div>


					<!-- Правая часть: Контакты и Поддержка -->
					<div>
						<h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6">Поддержка</h3>
						<div class="flex flex-col gap-4">
							<div class="flex flex-col gap-4" x-data="
							{ 
								copied: false, 
								copyEmail() { 
									const email = 'mapstorage.support@gmail.com';
									
									if (navigator.clipboard && window.isSecureContext) {
										navigator.clipboard.writeText(email).then(() => {
											this.finishCopy();
										});
									} else {
										const textArea = document.createElement('textarea');
										textArea.value = email;
										textArea.style.position = 'fixed';
										textArea.style.left = '-999999px';
										textArea.style.top = '-999999px';
										document.body.appendChild(textArea);
										textArea.focus();
										textArea.select();
										try {
											document.execCommand('copy');
											this.finishCopy();
										} catch (err) {
											console.error('Ошибка копирования:', err);
										}
										document.body.removeChild(textArea);
									}
								},
								finishCopy() {
									this.copied = true; 
									setTimeout(() => this.copied = false, 2000);
								}
							}"
							>
								<div class="flex items-center gap-2">
									<!-- Основная ссылка (открывает почту) -->
									@php
										$subject = "Поддержка MapStorage";
										$body = "Здравствуйте, команда MapStorage!\n\nМой вопрос/предложение:\n\n---\nОтправлено от: " . (Auth::check() ? Auth::user()->name : 'Гость');

										$mailLink = "mailto:mapstorage.support@gmail.com?" . http_build_query([
											'subject' => $subject,
											'body' => $body
										], '', '&', PHP_QUERY_RFC3986);
									@endphp

									<a href="{{ $mailLink }}"
										class="group flex items-center gap-3 text-gray-600 hover:text-pink-600 transition-colors">
										<div
											class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-pink-50 transition-colors">
											<svg class="w-5 h-5 text-gray-500 group-hover:text-pink-600" fill="none"
												stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
											</svg>
										</div>
										<div class="flex flex-col">
											<span
												class="text-[10px] uppercase font-black tracking-tighter text-gray-400 leading-none mb-1">E-mail</span>
											<span class="text-sm font-bold">mapstorage.support@gmail.com</span>
										</div>
									</a>

									<!-- Кнопка Копировать -->
									<button @click="copyEmail()"
										class="ml-2 p-1.5 text-gray-400 hover:text-pink-600 hover:bg-pink-50 rounded-md transition-all relative"
										title="Копировать адрес">
										<!-- Иконка копирования -->
										<svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
											fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
										</svg>
										<!-- Иконка галочки -->
										<svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg"
											class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24"
											stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M5 13l4 4L19 7" />
										</svg>

										<!-- Всплывающая подсказка "Скопировано!" -->
										<span x-show="copied" x-cloak x-transition
											class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] py-1 px-2 rounded shadow-lg">
											Скопировано!
										</span>
									</button>
								</div>

								<!-- GitHub -->
								<a href="https://github.com/Lirihiwa" target="_blank"
									class="group flex items-center gap-3 text-gray-600 hover:text-gray-900 transition-colors">
									<div
										class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-gray-200 transition-colors">
										<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
											<path
												d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
										</svg>
									</div>
									<span class="text-sm font-bold">GitHub</span>
								</a>

								<!-- Telegram -->
								<a href="https://t.me/Lirihiwa" target="_blank"
									class="group flex items-center gap-3 text-gray-600 hover:text-[#26A5E4] transition-colors">
									<div
										class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#26A5E4]/10 transition-colors">
										<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
											<path
												d="M11.944 0C5.346 0 0 5.348 0 11.944c0 6.595 5.346 11.944 11.944 11.944 6.596 0 11.944-5.349 11.944-11.944C23.888 5.348 18.54 0 11.944 0zm5.836 8.196l-1.9 8.948c-.143.642-.522.8-.106.335l-2.894-2.132-1.396 1.343c-.155.155-.285.285-.584.285l.208-2.942 5.353-4.838c.233-.207-.051-.322-.361-.116l-6.618 4.167-2.851-.89c-.619-.193-.632-.619.129-.916l11.144-4.295c.516-.188.966.119.768.905z" />
										</svg>
									</div>
									<span class="text-sm font-bold">Telegram</span>
								</a>
							</div>
						</div>
					</div>

					<!-- Нижняя плашка -->
					<div
						class="border-t border-gray-100 mt-12 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
						<p class="text-xs text-gray-400 font-medium">
							&copy; {{ date('Y') }} MAPSTORAGE. Все права защищены. Не является официальным ресурсом ppy
							Pty
							Ltd.
						</p>
						<div class="flex items-center gap-6">
							<span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Powered by
								Laravel</span>
						</div>
					</div>
				</div>
		</footer>
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
								class="group w-14 h-14 flex items-center justify-center text-pink-600 hover:bg-pink-50 rounded-full transition-all active:scale-90 shadow-sm border">
								<svg xmlns="http://www.w3.org/2000/svg" id="play-icon" class="w-14 h-14"
									viewBox="0 0 1024 1024">
									<path fill="currentColor"
										d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448s448-200.6 448-448S759.4 64 512 64m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372s372 166.6 372 372s-166.6 372-372 372" />
									<path fill="currentColor"
										d="m719.4 499.1l-296.1-215A15.9 15.9 0 0 0 398 297v430c0 13.1 14.8 20.5 25.3 12.9l296.1-215a15.9 15.9 0 0 0 0-25.8m-257.6 134V390.9L628.5 512z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" id="pause-icon" class="w-14 h-14 hidden"
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