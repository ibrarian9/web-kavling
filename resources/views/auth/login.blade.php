<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Proyek Properti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <h1 class="text-2xl font-bold tracking-tight text-white">Sistem Manajemen PT. Atlantik Perkasa Abadi</h1>
            <p class="text-slate-400 text-sm">Masuk ke sistem manajemen proyek kavling & rumah</p>
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

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full bg-slate-900 border border-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 outline-none transition">
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-600/20 transition duration-150 flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="border-t border-slate-700/80 pt-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3 text-center">Demo Fast Login (Akses Per Role)</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('switch-role', 'founder') }}" class="flex items-center justify-center p-2 bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/30 text-purple-300 rounded-xl text-xs font-medium transition">
                        Founder
                    </a>
                    <a href="{{ route('switch-role', 'supervisor') }}" class="flex items-center justify-center p-2 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 rounded-xl text-xs font-medium transition">
                        Supervisor
                    </a>
                    <a href="{{ route('switch-role', 'finance') }}" class="flex items-center justify-center p-2 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-xs font-medium transition">
                        Finance
                    </a>
                    <a href="{{ route('switch-role', 'marketing') }}" class="flex items-center justify-center p-2 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-300 rounded-xl text-xs font-medium transition">
                        Marketing
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500">
            Internal Application &copy; {{ date('Y') }} Manajemen Proyek Properti
        </p>
    </div>

</body>
</html>
