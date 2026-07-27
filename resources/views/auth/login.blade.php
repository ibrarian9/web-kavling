<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Manajemen Proyek Properti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-900 font-sans text-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mb-2 shadow-lg shadow-emerald-500/10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-white">Sistem Informasi Manajemen Proyek Properti</h1>
            <h1 class="text-xl font-bold tracking-tight text-white">PT. Atlantik Perkasa Abadi</h1>
        </div>

        <!-- Form Card -->
        <div class="bg-slate-800/80 backdrop-blur border border-slate-700 rounded-2xl p-6 shadow-2xl space-y-5">
            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 p-3 rounded-xl text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" required placeholder="email@kavling.com" class="w-full bg-slate-900 border border-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none transition">
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required placeholder="••••••••" class="w-full bg-slate-900 border border-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl pl-4 pr-11 py-2.5 text-sm text-white placeholder-slate-500 outline-none transition">
                        <button type="button" @click="showPassword = !showPassword" onclick="const p = document.getElementById('password'); p.type = (p.type === 'password' ? 'text' : 'password');" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-400 p-1.5 transition rounded-lg hover:bg-slate-800" title="Tampilkan / Sembunyikan Password">
                            <!-- Open Eye Icon -->
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Off Icon -->
                            <svg x-show="showPassword" style="display:none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.97 8.97 0 012.122-.363c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-600/20 transition duration-150 flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

        </div>

        <p class="text-center text-xs text-slate-500">
            Internal Application &copy; {{ date('Y') }} Manajemen Proyek Properti
        </p>
    </div>

    @livewireScripts
</body>
</html>
