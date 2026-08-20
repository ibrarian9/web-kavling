<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditAllButtonsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_wire_click_methods_exist_in_livewire_components(): void
    {
        $livewireDir = app_path('Livewire');
        $viewsDir = resource_path('views/livewire');

        $componentClasses = [];
        foreach (File::allFiles($livewireDir) as $file) {
            $relativePath = str_replace([$livewireDir . '/', '.php'], '', $file->getPathname());
            $className = 'App\\Livewire\\' . str_replace('/', '\\', $relativePath);
            if (class_exists($className)) {
                $componentClasses[$relativePath] = $className;
            }
        }

        $errors = [];
        $checkedButtons = 0;

        foreach (File::allFiles($viewsDir) as $viewFile) {
            $content = File::get($viewFile->getPathname());
            
            // Extract wire:click attributes
            preg_match_all('/wire:click(?:\.[a-zA-Z0-9_-]+)*=["\']([^"\']+)["\']/', $content, $matches);

            if (empty($matches[1])) {
                continue;
            }

            // Map specific views to their exact Livewire components
            $viewRelative = str_replace([$viewsDir . '/', '.blade.php'], '', $viewFile->getPathname());
            $matchingClass = null;

            $showPartials = [
                'projects/show', 'projects/partials/header', 'projects/partials/tab-units', 'projects/partials/tab-siteplan', 
                'projects/partials/tab-payments', 'projects/partials/tab-cashflow', 'projects/partials/modal-payment',
                'projects/partials/modal-legacy-sale', 'projects/partials/land-purchase-card', 
                'projects/partials/specifications-strip', 'projects/partials/tab-activity-reports'
            ];

            if (in_array($viewRelative, $showPartials) || str_starts_with($viewRelative, 'projects/show')) {
                $matchingClass = \App\Livewire\Projects\Show::class;
            } elseif (str_starts_with($viewRelative, 'projects/')) {
                $matchingClass = \App\Livewire\Projects\Index::class;
            } elseif (str_starts_with($viewRelative, 'units/legacy') || str_starts_with($viewRelative, 'units/partials/legacy-')) {
                $matchingClass = \App\Livewire\Units\LegacySale::class;
            } elseif (str_starts_with($viewRelative, 'units/show') || str_starts_with($viewRelative, 'units/partials/section-') || in_array($viewRelative, ['units/partials/modal-booking', 'units/partials/header-actions', 'units/partials/section-specifications', 'units/partials/section-timeline-installments', 'units/partials/modal-material-purchase', 'units/partials/modal-payroll-borongan', 'units/partials/modal-convert-cash', 'units/partials/modal-commission', 'units/partials/modal-direct-proposal', 'units/partials/modal-direct-spp', 'units/partials/modal-installment-payment', 'units/partials/modal-setup-installment', 'units/partials/modal-worker-assignment'])) {
                $matchingClass = \App\Livewire\Units\Show::class;
            } elseif (str_starts_with($viewRelative, 'units/')) {
                $matchingClass = \App\Livewire\Units\Index::class;
            } elseif (str_starts_with($viewRelative, 'bookings/')) {
                $matchingClass = \App\Livewire\Bookings\Index::class;
            } elseif (str_starts_with($viewRelative, 'installments/')) {
                $matchingClass = \App\Livewire\Installments\Index::class;
            } elseif (str_starts_with($viewRelative, 'proposals/')) {
                $matchingClass = \App\Livewire\Proposals\Index::class;
            } elseif (str_starts_with($viewRelative, 'documents/')) {
                $matchingClass = \App\Livewire\Documents\Index::class;
            } elseif (str_starts_with($viewRelative, 'workers/')) {
                $matchingClass = \App\Livewire\Workers\Index::class;
            } elseif (str_starts_with($viewRelative, 'field-expenses/')) {
                $matchingClass = \App\Livewire\FieldExpenses\Index::class;
            } elseif (str_starts_with($viewRelative, 'employee-salaries/')) {
                $matchingClass = \App\Livewire\EmployeeSalaries\Index::class;
            } elseif (str_starts_with($viewRelative, 'payables/')) {
                $matchingClass = \App\Livewire\Payables\Index::class;
            } elseif (str_starts_with($viewRelative, 'manual-invoices/')) {
                $matchingClass = \App\Livewire\ManualInvoices\Index::class;
            } elseif (str_starts_with($viewRelative, 'cashflow/')) {
                $matchingClass = \App\Livewire\Cashflow\Index::class;
            } elseif (str_starts_with($viewRelative, 'daily-activity-reports/')) {
                $matchingClass = \App\Livewire\DailyActivityReports\Index::class;
            } elseif (str_starts_with($viewRelative, 'activity-logs/')) {
                $matchingClass = \App\Livewire\ActivityLogs\Index::class;
            } elseif (str_starts_with($viewRelative, 'users/')) {
                $matchingClass = \App\Livewire\Users\Index::class;
            } elseif (str_starts_with($viewRelative, 'profile/')) {
                $matchingClass = \App\Livewire\Profile\Index::class;
            } elseif (str_starts_with($viewRelative, 'tutorial/')) {
                $matchingClass = \App\Livewire\Tutorial\Index::class;
            } elseif ($viewRelative === 'dashboard') {
                $matchingClass = \App\Livewire\Dashboard::class;
            } elseif ($viewRelative === 'global-search') {
                $matchingClass = \App\Livewire\GlobalSearch::class;
            }

            if (!$matchingClass) {
                continue;
            }

            $reflection = new \ReflectionClass($matchingClass);

            foreach ($matches[1] as $action) {
                $action = trim($action);
                
                // Skip magic actions like $set, $toggle, $refresh, $dispatch, $parent
                if (str_starts_with($action, '$')) {
                    $checkedButtons++;
                    continue;
                }

                // Extract method name (e.g., "deleteProject(1)" -> "deleteProject")
                if (preg_match('/^([a-zA-Z0-9_]+)/', $action, $methodMatch)) {
                    $methodName = $methodMatch[1];
                    $checkedButtons++;

                    if (!$reflection->hasMethod($methodName)) {
                        // Check if it exists in traits or Livewire Base Component
                        $hasMethod = false;
                        $class = $matchingClass;
                        while ($class) {
                            $r = new \ReflectionClass($class);
                            if ($r->hasMethod($methodName)) {
                                $hasMethod = true;
                                break;
                            }
                            foreach ($r->getTraits() as $trait) {
                                if ($trait->hasMethod($methodName)) {
                                    $hasMethod = true;
                                    break 2;
                                }
                            }
                            $class = $r->getParentClass() ? $r->getParentClass()->getName() : null;
                        }

                        if (!$hasMethod) {
                            $errors[] = "View [{$viewRelative}] calls non-existent method [{$methodName}] on [{$matchingClass}] (Full action: '{$action}')";
                        }
                    }
                }
            }
        }

        $this->assertEmpty($errors, "Found broken wire:click button actions:\n" . implode("\n", $errors));
        $this->assertGreaterThan(50, $checkedButtons, "Checked buttons should be more than 50");
    }

    public function test_all_named_routes_in_blade_views_exist(): void
    {
        $viewsDir = resource_path('views');
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $namedRoutes = [];
        foreach ($routes as $route) {
            if ($route->getName()) {
                $namedRoutes[$route->getName()] = true;
            }
        }

        $errors = [];
        $checkedRoutes = 0;

        foreach (File::allFiles($viewsDir) as $file) {
            $content = File::get($file->getPathname());
            
            // Extract route('name', ...) excluding Route::has('name')
            preg_match_all('/(?<!Route::has\([\'"])route\([\'"]([a-zA-Z0-9_\.\-]+)[\'"]/', $content, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $routeName) {
                    if (str_contains($content, "Route::has('{$routeName}')") || str_contains($content, "Route::has(\"{$routeName}\")")) {
                        continue;
                    }
                    $checkedRoutes++;
                    if (!isset($namedRoutes[$routeName])) {
                        $rel = str_replace($viewsDir . '/', '', $file->getPathname());
                        $errors[] = "View [{$rel}] references non-existent route: '{$routeName}'";
                    }
                }
            }
        }

        $this->assertEmpty($errors, "Found broken named route references:\n" . implode("\n", $errors));
        $this->assertGreaterThan(10, $checkedRoutes);
    }

    public function test_all_confirm_modal_actions_have_valid_wire_methods(): void
    {
        $viewsDir = resource_path('views/livewire');
        $errors = [];
        $checkedModals = 0;

        foreach (File::allFiles($viewsDir) as $viewFile) {
            $content = File::get($viewFile->getPathname());
            
            // Extract onConfirm: () => $wire.methodName(...)
            preg_match_all('/onConfirm:\s*\(\)\s*=>\s*\$wire\.([a-zA-Z0-9_]+)/', $content, $matches);

            if (empty($matches[1])) {
                continue;
            }

            $viewRelative = str_replace([$viewsDir . '/', '.blade.php'], '', $viewFile->getPathname());
            $matchingClass = null;

            $showPartials = [
                'projects/show', 'projects/partials/header', 'projects/partials/tab-units', 'projects/partials/tab-siteplan', 
                'projects/partials/tab-payments', 'projects/partials/tab-cashflow', 'projects/partials/modal-payment',
                'projects/partials/modal-legacy-sale', 'projects/partials/land-purchase-card', 
                'projects/partials/specifications-strip', 'projects/partials/tab-activity-reports'
            ];

            if (in_array($viewRelative, $showPartials) || str_starts_with($viewRelative, 'projects/show')) {
                $matchingClass = \App\Livewire\Projects\Show::class;
            } elseif (str_starts_with($viewRelative, 'projects/')) {
                $matchingClass = \App\Livewire\Projects\Index::class;
            } elseif (str_starts_with($viewRelative, 'units/legacy') || str_starts_with($viewRelative, 'units/partials/legacy-')) {
                $matchingClass = \App\Livewire\Units\LegacySale::class;
            } elseif (str_starts_with($viewRelative, 'units/show') || str_starts_with($viewRelative, 'units/partials/section-') || in_array($viewRelative, ['units/partials/modal-booking', 'units/partials/header-actions', 'units/partials/section-specifications', 'units/partials/section-timeline-installments', 'units/partials/modal-material-purchase', 'units/partials/modal-payroll-borongan', 'units/partials/modal-convert-cash', 'units/partials/modal-commission', 'units/partials/modal-direct-proposal', 'units/partials/modal-direct-spp', 'units/partials/modal-installment-payment', 'units/partials/modal-setup-installment', 'units/partials/modal-worker-assignment'])) {
                $matchingClass = \App\Livewire\Units\Show::class;
            } elseif (str_starts_with($viewRelative, 'units/')) {
                $matchingClass = \App\Livewire\Units\Index::class;
            } elseif (str_starts_with($viewRelative, 'bookings/')) {
                $matchingClass = \App\Livewire\Bookings\Index::class;
            } elseif (str_starts_with($viewRelative, 'installments/')) {
                $matchingClass = \App\Livewire\Installments\Index::class;
            } elseif (str_starts_with($viewRelative, 'proposals/')) {
                $matchingClass = \App\Livewire\Proposals\Index::class;
            } elseif (str_starts_with($viewRelative, 'documents/')) {
                $matchingClass = \App\Livewire\Documents\Index::class;
            } elseif (str_starts_with($viewRelative, 'workers/')) {
                $matchingClass = \App\Livewire\Workers\Index::class;
            } elseif (str_starts_with($viewRelative, 'field-expenses/')) {
                $matchingClass = \App\Livewire\FieldExpenses\Index::class;
            } elseif (str_starts_with($viewRelative, 'employee-salaries/')) {
                $matchingClass = \App\Livewire\EmployeeSalaries\Index::class;
            } elseif (str_starts_with($viewRelative, 'payables/')) {
                $matchingClass = \App\Livewire\Payables\Index::class;
            } elseif (str_starts_with($viewRelative, 'manual-invoices/')) {
                $matchingClass = \App\Livewire\ManualInvoices\Index::class;
            } elseif (str_starts_with($viewRelative, 'cashflow/')) {
                $matchingClass = \App\Livewire\Cashflow\Index::class;
            } elseif (str_starts_with($viewRelative, 'daily-activity-reports/')) {
                $matchingClass = \App\Livewire\DailyActivityReports\Index::class;
            } elseif (str_starts_with($viewRelative, 'activity-logs/')) {
                $matchingClass = \App\Livewire\ActivityLogs\Index::class;
            } elseif (str_starts_with($viewRelative, 'users/')) {
                $matchingClass = \App\Livewire\Users\Index::class;
            } elseif (str_starts_with($viewRelative, 'profile/')) {
                $matchingClass = \App\Livewire\Profile\Index::class;
            } elseif (str_starts_with($viewRelative, 'tutorial/')) {
                $matchingClass = \App\Livewire\Tutorial\Index::class;
            } elseif ($viewRelative === 'dashboard') {
                $matchingClass = \App\Livewire\Dashboard::class;
            }

            if (!$matchingClass) {
                continue;
            }

            foreach ($matches[1] as $methodName) {
                $checkedModals++;
                $class = $matchingClass;
                $hasMethod = false;
                while ($class) {
                    $r = new \ReflectionClass($class);
                    if ($r->hasMethod($methodName)) {
                        $hasMethod = true;
                        break;
                    }
                    foreach ($r->getTraits() as $trait) {
                        if ($trait->hasMethod($methodName)) {
                            $hasMethod = true;
                            break 2;
                        }
                    }
                    $class = $r->getParentClass() ? $r->getParentClass()->getName() : null;
                }

                if (!$hasMethod) {
                    $errors[] = "View [{$viewRelative}] confirm modal calls non-existent method [{$methodName}] on [{$matchingClass}]";
                }
            }
        }

        $this->assertEmpty($errors, "Found broken confirm modal actions:\n" . implode("\n", $errors));
        $this->assertGreaterThan(10, $checkedModals);
    }

    public function test_no_views_use_native_wire_confirm_or_alert_and_all_use_micromodal(): void
    {
        $viewsDir = resource_path('views');
        $wireConfirmFound = [];
        $nativeAlertFound = [];

        foreach (File::allFiles($viewsDir) as $file) {
            $content = File::get($file->getPathname());
            $rel = str_replace($viewsDir . '/', '', $file->getPathname());

            if (str_contains($content, 'wire:confirm')) {
                $wireConfirmFound[] = $rel;
            }

            if (preg_match('/(?<!confirmModalAction)\b(window\.)?(alert|confirm)\s*\(/', $content)) {
                // Ignore script in app.js or layouts if not inline blade alert
                if (!str_contains($rel, 'vendor/')) {
                    $nativeAlertFound[] = $rel;
                }
            }
        }

        $this->assertEmpty($wireConfirmFound, "Found views still using native wire:confirm:\n" . implode("\n", $wireConfirmFound));
        $this->assertEmpty($nativeAlertFound, "Found views using native alert/confirm:\n" . implode("\n", $nativeAlertFound));
    }

    public function test_interactive_execution_of_all_major_module_action_buttons(): void
    {
        Role::findOrCreate('founder', 'web');

        $founder = User::create([
            'name' => 'Founder Button Test',
            'email' => 'founder_audit@example.com',
            'password' => bcrypt('password'),
            'role' => 'founder',
            'is_active' => true,
        ]);

        $this->actingAs($founder);

        // 1. Projects Index
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 2. Units Index
        Livewire::test(\App\Livewire\Units\Index::class)
            ->call('openModal')
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 3. Bookings Index
        Livewire::test(\App\Livewire\Bookings\Index::class)
            ->set('showModal', true)
            ->assertSet('showModal', true)
            ->set('showModal', false)
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 4. Proposals Index
        Livewire::test(\App\Livewire\Proposals\Index::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('showCreateModal', false)
            ->assertSet('showCreateModal', false)
            ->assertStatus(200);

        // 5. Workers Index
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->call('create')
            ->assertSet('showModal', true)
            ->set('showModal', false)
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 6. Field Expenses Index
        Livewire::test(\App\Livewire\FieldExpenses\Index::class)
            ->call('openViewer', 'Test Document', 'pdf', 'http://example.com/test.pdf')
            ->assertSet('showViewerModal', true)
            ->call('closeViewer')
            ->assertSet('showViewerModal', false)
            ->assertStatus(200);

        // 7. Users Index
        Livewire::test(\App\Livewire\Users\Index::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->set('showModal', false)
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 8. Manual Invoices Index
        Livewire::test(\App\Livewire\ManualInvoices\Index::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertStatus(200);

        // 9. Cashflow Index
        Livewire::test(\App\Livewire\Cashflow\Index::class)
            ->call('openManualModal')
            ->assertSet('showManualModal', true)
            ->set('showManualModal', false)
            ->assertSet('showManualModal', false)
            ->assertStatus(200);

        // 10. Employee Salaries Index
        Livewire::test(\App\Livewire\EmployeeSalaries\Index::class)
            ->call('openSalaryModal')
            ->assertSet('showSalaryModal', true)
            ->set('showSalaryModal', false)
            ->assertSet('showSalaryModal', false)
            ->assertStatus(200);

        // 11. Payables Index Tabs
        Livewire::test(\App\Livewire\Payables\Index::class)
            ->call('setTab', 'material_bills')
            ->assertSet('activeTab', 'material_bills')
            ->call('setTab', 'worker_payrolls')
            ->assertSet('activeTab', 'worker_payrolls')
            ->call('setTab', 'unit_commissions')
            ->assertSet('activeTab', 'unit_commissions')
            ->call('setTab', 'company_receivables')
            ->assertSet('activeTab', 'company_receivables')
            ->call('setTab', 'settled_history')
            ->assertSet('activeTab', 'settled_history')
            ->assertStatus(200);

        // 12. Daily Activity Reports Index
        Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
            ->call('openCreateModal')
            ->assertSet('showReportModal', true)
            ->set('showReportModal', false)
            ->assertSet('showReportModal', false)
            ->assertStatus(200);

        // 13. Activity Logs Index Tabs
        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->call('setTab', 'database')
            ->assertSet('activeTab', 'database')
            ->call('setTab', 'laravel_log')
            ->assertSet('activeTab', 'laravel_log')
            ->call('setTab', 'notifications')
            ->assertSet('activeTab', 'notifications')
            ->assertStatus(200);

        // 14. Global Search
        Livewire::test(\App\Livewire\GlobalSearch::class)
            ->call('openModal')
            ->assertSet('isOpen', true)
            ->set('query', 'Unit')
            ->call('closeModal')
            ->assertSet('isOpen', false)
            ->assertStatus(200);
    }
}
