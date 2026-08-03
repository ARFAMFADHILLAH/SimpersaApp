<x-guest-layout>
    <div class="text-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Buat Akun Baru</h3>
        <p class="text-sm text-gray-500 mt-1">Daftar untuk menjadi pelanggan SIMPERSA</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Pilihan Role / Peran -->
        <div class="mt-4">
            <x-input-label for="role_id" :value="__('Daftar Sebagai')" />
            
            <select id="role_id" name="role_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" required>
                <option value="6" {{ old('role_id') == 6 ? 'selected' : '' }}>Pelanggan / Warga</option>
            </select>

            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-500 hover:text-emerald-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-200 transition">
                {{ __('Daftar') }}
            </button>
        </div>
    </form>

    <div class="text-center mt-6 pt-5 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Dengan mendaftar, Anda menyetujui
            <a href="#" class="font-semibold text-emerald-700 hover:underline">Syaras & Ketentuan</a>
        </p>
    </div>
</x-guest-layout>