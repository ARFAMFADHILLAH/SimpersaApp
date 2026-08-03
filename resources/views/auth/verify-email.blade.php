<x-guest-layout>
    <div class="text-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Verifikasi Email</h3>
        <p class="text-sm text-gray-500 mt-1">Konfirmasi alamat email Anda</p>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisa verifikasi email Anda dengan mengklik tautan yang kami kirimkan. Jika tidak menerima email, kami akan kirim ulang.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-600 bg-emerald-50 p-3 rounded-xl">
            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Kirim Ulang Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-500 hover:text-emerald-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>