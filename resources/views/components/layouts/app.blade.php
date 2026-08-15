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
<body x-data="{ 
          mobileMenuOpen: false, 
          sidebarExpanded: localStorage.getItem('sidebar_expanded') !== 'false',
          openSections: JSON.parse(localStorage.getItem('sidebar_accordion_sections') || '{}'),
          toggleSidebar() {
              this.sidebarExpanded = !this.sidebarExpanded;
              localStorage.setItem('sidebar_expanded', this.sidebarExpanded);
          },
          toggleSection(key) {
              this.openSections[key] = !this.isOpen(key);
              localStorage.setItem('sidebar_accordion_sections', JSON.stringify(this.openSections));
          },
          isOpen(key) {
              return this.openSections[key] === undefined ? true : !!this.openSections[key];
          },
          init() {
              this.syncActiveSection();
              
              const restoreNavScroll = () => {
                  const nav = document.getElementById('desktop-sidebar-nav');
                  const saved = sessionStorage.getItem('sidebar_nav_scroll');
                  if (nav && saved !== null) {
                      nav.scrollTop = parseInt(saved, 10);
                  }
              };

              restoreNavScroll();

              document.addEventListener('livewire:navigating', () => {
                  const nav = document.getElementById('desktop-sidebar-nav');
                  if (nav) {
                      sessionStorage.setItem('sidebar_nav_scroll', nav.scrollTop);
                  }
              });

              document.addEventListener('livewire:navigated', () => {
                  this.syncActiveSection();
                  restoreNavScroll();
              });
          },
          syncActiveSection() {
              const path = window.location.pathname;
              if (path.includes('/projects') || path.includes('/units')) {
                  this.openSections['property'] = true;
              } else if (path.includes('/workers') || path.includes('/field-expenses')) {
                  this.openSections['field'] = true;
              } else if (path.includes('/daily-activity-reports') || path.includes('/bookings') || path.includes('/proposals') || path.includes('/documents')) {
                  this.openSections['sales'] = true;
              } else if (path.includes('/installments') || path.includes('/cashflow') || path.includes('/payables') || path.includes('/manual-invoices')) {
                  this.openSections['finance'] = true;
              } else if (path.includes('/profile') || path.includes('/employee-salaries') || path.includes('/users') || path.includes('/activity-logs')) {
                  this.openSections['admin'] = true;
              }
          }
      }" 
      x-effect="document.body.classList.toggle('overflow-hidden', mobileMenuOpen)" 
      @keydown.window.escape="mobileMenuOpen = false" 
      class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col antialiased">

    <div class="flex flex-1 min-h-screen">
        <!-- Desktop Sidebar Navigation (Sticky) -->
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
