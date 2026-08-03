<x-guest-layout>
    <div class="text-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Lupa Password</h3>
        <p class="text-sm text-gray-500 mt-1">Masukkan email Anda, kami akan kirim tautan reset password</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-500 hover:text-emerald-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                {{ __('Kembali ke Login') }}
            </a>

            <x-primary-button>
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>