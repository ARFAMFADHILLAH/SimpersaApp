<x-guest-layout>
    <div class="text-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Konfirmasi Password</h3>
        <p class="text-sm text-gray-500 mt-1">Konfirmasi password Anda untuk melanjutkan</p>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ini area aman aplikasi. Harap konfirmasi password sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                {{ __('Konfirmasi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>