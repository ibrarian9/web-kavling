<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - SIM Proyek Properti & Keuangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

            <!-- Main Page Content Slot -->
            <main class="p-6 lg:p-8 flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts

    <!-- MicroModal Global Confirmation Dialog -->
    @include('components.layouts.partials.confirm-modal')

    <!-- Floating Toast Notifications Stack Component -->
    @include('components.layouts.partials.toast-notifications')
</body>
</html>
