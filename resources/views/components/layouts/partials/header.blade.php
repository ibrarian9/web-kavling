<!-- Top Navigation Bar Header -->
<header class="min-h-16 py-2 bg-white border-b border-slate-200 px-3 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs relative">

    <!-- Top Loading Progress Bar (0% to 100% Green Line) -->
    <div x-data="{ 
             loading: false, 
             progress: 0, 
             timer: null,
             start() {
                 this.loading = true;
                 this.progress = 15;
                 if (this.timer) clearInterval(this.timer);
                 this.timer = setInterval(() => {
                     if (this.progress < 90) {
                         this.progress += Math.floor(Math.random() * 12) + 6;
                     }
                 }, 70);
             },
             finish() {
                 this.progress = 100;
                 if (this.timer) clearInterval(this.timer);
                 setTimeout(() => {
                     this.loading = false;
                     setTimeout(() => { this.progress = 0; }, 300);
                 }, 250);
             }
         }"
         x-init="
             document.addEventListener('livewire:navigating', () => start());
             document.addEventListener('livewire:navigated', () => finish());
             window.addEventListener('beforeunload', () => start());
             document.addEventListener('click', (e) => {
                 const a = e.target.closest('a');
                 if (a && a.href && !a.href.startsWith('#') && !a.target && !a.hasAttribute('download')) {
                     start();
                 }
             });
             Livewire.hook('commit', ({ succeed, fail }) => {
                 start();
                 succeed(() => finish());
                 fail(() => finish());
             });
         "
         class="absolute top-0 left-0 right-0 h-1 z-50 overflow-hidden pointer-events-none bg-slate-100/50">
        <div x-show="loading" 
             x-transition:leave="transition ease-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="h-full bg-emerald-500 shadow-[0_0_10px_#10b981,0_0_5px_#10b981] transition-all duration-150 ease-out"
             :style="`width: ${progress}%`"></div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 mr-2">
        <!-- Mobile Drawer Toggle -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 md:hidden focus:outline-none shrink-0" aria-label="Buka Menu Mobile">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <!-- Desktop Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 hidden md:flex items-center justify-center transition focus:outline-none shrink-0" title="Munculkan / Sembunyikan Sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16"/></svg>
        </button>

        <h1 class="font-bold text-xs sm:text-lg text-slate-900 tracking-tight leading-tight line-clamp-2 break-words">{{ $header ?? $title ?? 'Dashboard' }}</h1>
    </div>

    <div class="flex items-center gap-3">
        <!-- Global Search Command Palette (Ctrl + K) -->
        @livewire('global-search')

        <div class="text-right hidden md:block">
            <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</p>
            <p class="text-[11px] text-slate-500 font-mono">{{ auth()->user()->email }}</p>
        </div>

        @php $role = auth()->user()->role ?? 'guest'; @endphp
        @if($role === 'founder')
            <span class="badge-role-founder">Founder</span>
        @elseif($role === 'supervisor')
            <span class="badge-role-supervisor">Supervisor</span>
        @elseif($role === 'finance')
            <span class="badge-role-finance">Finance</span>
        @elseif($role === 'pengawas_project')
            <span class="badge-role-pengawas">Pengawas</span>
        @else
            <span class="badge-role-marketing">Marketing</span>
        @endif
    </div>
</header>
