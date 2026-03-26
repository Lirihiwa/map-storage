<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Обновить пароль') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Используйте длинный случайный пароль, чтобы обеспечить безопасность вашего профиля.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Текущий пароль')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full focus:ring-pink-500 focus:border-pink-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Новый пароль')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full focus:ring-pink-500 focus:border-pink-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Подтвердите пароль')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full focus:ring-pink-500 focus:border-pink-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-pink-600 hover:bg-pink-700 active:bg-pink-800 transition shadow-sm">
                {{ __('Сохранить') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Сохранено.') }}</p>
            @endif
        </div>
    </form>
</section>