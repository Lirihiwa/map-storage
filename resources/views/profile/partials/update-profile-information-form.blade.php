<section>
	<header>
		<h2 class="text-lg font-medium text-gray-900">
			{{ __('Информация профиля') }}
		</h2>
		<p class="mt-1 text-sm text-gray-600">
			{{ __("Обновите данные вашего аккаунта и выберите аватар.") }}
		</p>
	</header>

	<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
		@csrf
		@method('patch')

		<!-- Блок Аватара -->
		<div class="flex items-center gap-6 mb-8">
			<div class="shrink-0">
				<!-- Обертка с фиксированным размером -->
				<div class="h-24 w-24 rounded-full border-2 border-pink-500 shadow-md overflow-hidden bg-gray-100">
					<img id="avatar-preview"
						src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : 'https://www.gravatar.com/avatar/000?d=mp' }}"
						class="h-full w-full object-cover" alt="Аватар">
				</div>
			</div>

			<div class="flex-1">
				<x-input-label for="avatar" :value="__('Сменить фото профиля')" class="mb-2" />
				<input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-pink-50 file:text-pink-700
            hover:file:bg-pink-100 transition-all cursor-pointer" accept="image/*"
					onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])" />
				<p class="mt-1 text-xs text-gray-400 italic">Рекомендуется квадратное изображение, до 8МБ.</p>
				<x-input-error class="mt-2" :messages="$errors->get('avatar')" />
			</div>
		</div>

		<!-- Поле Имя -->
		<div>
			<x-input-label for="name" :value="__('Имя пользователя')" />
			<x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
				required autofocus autocomplete="name" />
			<x-input-error class="mt-2" :messages="$errors->get('name')" />
		</div>

		<!-- Поле Email -->
		<div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

		<div class="flex items-center gap-4">
			<x-primary-button
				class="bg-pink-600 hover:bg-pink-700 active:bg-pink-800">{{ __('Сохранить') }}</x-primary-button>
			@if (session('status') === 'profile-updated')
				<p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
					class="text-sm text-gray-600">{{ __('Сохранено.') }}</p>
			@endif
		</div>
	</form>
</section>