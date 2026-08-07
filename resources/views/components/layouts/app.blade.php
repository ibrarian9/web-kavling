<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - SIM Proyek Properti & Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Top SPA Loading Progress Bar Style */
        #nprogress .bar, .livewire-progress-bar {
            background: #10b981 !important; /* emerald-500 */
            height: 3px !important;
            box-shadow: 0 0 10px #10b981, 0 0 5px #10b981;
        }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false, sidebarOpen: true }" x-effect="document.body.classList.toggle('overflow-hidden', mobileMenuOpen)" @keydown.window.escape="mobileMenuOpen = false" class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col antialiased">

    <div class="flex flex-1 min-h-screen">
        <!-- Desktop Sidebar Navigation -->
        @include('components.layouts.partials.sidebar-desktop')

        <!-- Mobile Sidebar Drawer Overlay & Content -->
        @include('components.layouts.partials.sidebar-mobile')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navigation Bar Header -->
            @include('components.layouts.partials.header')

            <!-- Alerts Banner -->
            @if (session()->has('success'))
                <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Page Content Slot -->
            <main class="p-6 lg:p-8 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- MicroModal Global Confirmation Dialog -->
    @include('components.layouts.partials.confirm-modal')

    @livewireScripts
</body>
</html>
