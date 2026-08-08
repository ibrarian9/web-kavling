<!-- Toast Notifications Floating Card Stack Container -->
<div x-data="{
         toasts: [],
         addToast(raw) {
             if (!raw) return;
             let data = raw;
             if (Array.isArray(raw)) {
                 data = raw[0] || {};
             }
             if (typeof data === 'string') {
                 data = { message: data, type: 'success' };
             }
             if (typeof data !== 'object') return;

             const id = Date.now() + Math.random();
             const type = data.type || 'success';
             const title = data.title || (type === 'success' ? 'Berhasil!' : (type === 'error' ? 'Gagal!' : (type === 'warning' ? 'Peringatan!' : 'Informasi')));
             const message = data.message || data.text || (typeof raw === 'string' ? raw : '');
             if (!message) return;

             // Prevent duplicate toasts with identical message within 1000ms
             const now = Date.now();
             const lastToast = this.toasts[this.toasts.length - 1];
             if (lastToast && lastToast.message === message && (now - (lastToast.createdAt || 0) < 1000)) {
                 return;
             }

             const duration = data.duration || 4000;

             const toast = {
                 id: id,
                 createdAt: now,
                 type: type,
                 title: title,
                 message: message,
                 duration: duration,
                 progress: 100,
                 timer: null,
                 progressInterval: null,
                 visible: true
             };

             this.toasts.push(toast);
             const item = this.toasts[this.toasts.length - 1];

             const step = 50;
             const decrement = (step / duration) * 100;
             
             item.progressInterval = setInterval(() => {
                 if (item.progress > 0) {
                     item.progress -= decrement;
                 }
             }, step);

             item.timer = setTimeout(() => {
                 this.removeToast(id);
             }, duration);
         },
         removeToast(id) {
             const index = this.toasts.findIndex(t => t.id === id);
             if (index !== -1) {
                 this.toasts[index].visible = false;
                 if (this.toasts[index].timer) clearTimeout(this.toasts[index].timer);
                 if (this.toasts[index].progressInterval) clearInterval(this.toasts[index].progressInterval);
                 setTimeout(() => {
                     this.toasts = this.toasts.filter(t => t.id !== id);
                 }, 300);
             }
         }
     }"
     x-init="
         window.showToast = (data) => addToast(data);

         @if (session()->has('success'))
             addToast({ type: 'success', title: 'Berhasil!', message: @js(session('success')) });
         @endif

         @if (session()->has('error'))
             addToast({ type: 'error', title: 'Gagal!', message: @js(session('error')) });
         @endif

         @if (session()->has('warning'))
             addToast({ type: 'warning', title: 'Peringatan!', message: @js(session('warning')) });
         @endif

         @if (session()->has('info'))
             addToast({ type: 'info', title: 'Informasi', message: @js(session('info')) });
         @endif

         const setupLivewireListeners = () => {
             if (window.Livewire && !window._livewireToastBound) {
                 window._livewireToastBound = true;
                 Livewire.on('notify', (...args) => {
                     let payload = args[0];
                     if (Array.isArray(payload)) payload = payload[0];
                     addToast(payload);
                 });
             }
         };

         if (window.Livewire) {
             setupLivewireListeners();
         } else {
             document.addEventListener('livewire:initialized', setupLivewireListeners);
         }
     "
     @notify.window="addToast($event.detail)"
     class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0">

    <template x-for="t in toasts" :key="t.id">
        <div x-show="t.visible"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full opacity-0 scale-95"
             x-transition:enter-end="translate-x-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0 opacity-100 scale-100"
             x-transition:leave-end="translate-x-full opacity-0 scale-95"
             class="pointer-events-auto relative overflow-hidden rounded-2xl bg-slate-900/95 border border-slate-700/80 shadow-2xl p-4 text-white backdrop-blur-md transition-all duration-300"
             :class="{
                 'border-l-4 border-l-emerald-500': t.type === 'success',
                 'border-l-4 border-l-rose-500': t.type === 'error',
                 'border-l-4 border-l-amber-500': t.type === 'warning',
                 'border-l-4 border-l-teal-500': t.type === 'info'
             }">

            <div class="flex items-start gap-3">
                <!-- Icon Indicator -->
                <div class="p-2 rounded-xl shrink-0"
                     :class="{
                         'bg-emerald-500/20 text-emerald-400': t.type === 'success',
                         'bg-rose-500/20 text-rose-400': t.type === 'error',
                         'bg-amber-500/20 text-amber-400': t.type === 'warning',
                         'bg-teal-500/20 text-teal-400': t.type === 'info'
                     }">
                    <template x-if="t.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="t.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </template>
                    <template x-if="t.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                    <template x-if="t.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                </div>

                <!-- Text Body -->
                <div class="flex-1 min-w-0 pr-2">
                    <h4 class="font-extrabold text-xs tracking-tight text-white" x-text="t.title"></h4>
                    <p class="text-[11px] text-slate-300 font-medium leading-relaxed mt-0.5" x-text="t.message"></p>
                </div>

                <!-- Dismiss Button -->
                <button @click="removeToast(t.id)" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition focus:outline-none shrink-0" aria-label="Tutup Notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Auto-Dismiss Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-800/80 overflow-hidden">
                <div class="h-full transition-all duration-75 ease-linear"
                     :class="{
                         'bg-emerald-500': t.type === 'success',
                         'bg-rose-500': t.type === 'error',
                         'bg-amber-500': t.type === 'warning',
                         'bg-teal-500': t.type === 'info'
                     }"
                     :style="`width: ${t.progress}%`"></div>
            </div>
        </div>
    </template>
</div>
