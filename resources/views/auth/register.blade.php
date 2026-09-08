<x-guest-layout>
    <div class="mb-8 flex flex-col items-center text-center">
        {{-- Wadah Logo SIMJ --}}
        <div class="relative bg-white p-3 rounded-2xl shadow-md border border-slate-100 flex items-center justify-center mb-4 transition-transform hover:scale-105 duration-300">
            <img src="{{ asset('images/Logosimj.png') }}" alt="Logo SIMJ" class="w-12 h-12 object-contain">
        </div>
        
        {{-- Teks Judul Pendaftaran --}}
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Buat akun petugas baru untuk sistem manajemen <br><span class="font-bold text-slate-700">Mustika Jaya 3</span></p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" />
            <x-text-input id="name" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Pengguna')" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" />
            <x-text-input id="email" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@mustikajaya.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" />
            <x-text-input id="password" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" />
            <x-text-input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        {{-- Bagian Tombol Aksi --}}
        <div class="flex flex-col gap-4 pt-2">
            {{-- Tombol Registrasi Utama --}}
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-lg shadow-slate-100 active:scale-[0.99] transition-all cursor-pointer flex justify-center items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                {{ __('Daftarkan Akun Baru') }}
            </button>

            {{-- Link Kembali ke Login --}}
            <div class="text-center">
                <span class="text-xs text-slate-500">Sudah punya akun petugas?</span>
                <a class="text-xs font-bold text-slate-700 hover:text-slate-900 underline decoration-2 underline-offset-4 ml-1 transition-colors" href="{{ route('login') }}">
                    {{ __('Masuk di sini') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>