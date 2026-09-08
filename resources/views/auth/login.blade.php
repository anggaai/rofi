<x-guest-layout>
    <div class="mb-8 flex flex-col items-center text-center">
        {{-- Wadah Logo SIMJ Baru --}}
        <div class="relative bg-white p-3 rounded-2xl shadow-md border border-slate-100 flex items-center justify-center mb-4 transition-transform hover:scale-105 duration-300">
            <img src="{{ asset('images/Logosimj.png') }}" alt="Logo SIMJ" class="w-12 h-12 object-contain">
        </div>
        
        {{-- Teks Sambutan Aplikasi --}}
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun Anda untuk mengelola inventaris <br><span class="font-bold text-slate-700">Mustika Jaya 3</span></p>
    </div>

    <x-auth-session-status class="mb-4 p-3 bg-green-50 border border-green-200 text-green-600 rounded-xl text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Pengguna')" class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1" />
            <div class="relative">
                <x-text-input id="email" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@mustikajaya.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Kata Sandi')" class="text-xs font-bold text-slate-500 uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors" href="{{ route('password.request') }}">
                        {{ __('Lupa Sandi?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full px-4 py-3 bg-slate-50 border-slate-200 focus:border-slate-900 focus:ring-slate-950 rounded-xl text-sm transition-all placeholder:text-slate-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-md border-slate-300 text-slate-900 shadow-sm focus:ring-slate-950 accent-slate-900 w-4 h-4 cursor-pointer" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-600 group-hover:text-slate-900 transition-colors">{{ __('Ingat saya di perangkat ini') }}</span>
            </label>
        </div>

        {{-- Tombol Login Utama Berwarna Slate Elegan --}}
        <div class="pt-2">
            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-lg shadow-slate-100 active:scale-[0.99] transition-all cursor-pointer flex justify-center items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                {{ __('Masuk ke Aplikasi') }}
            </button>
        </div>
    </form>
</x-guest-layout>