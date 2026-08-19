<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
    $this->marketing = User::factory()->create(['role' => 'marketing']);
    $this->supervisor = User::factory()->create(['role' => 'supervisor']);
    $this->finance = User::factory()->create(['role' => 'finance']);
});

test('all major livewire components mount and handle search/filters cleanly', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Proyek Audit Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'AUDIT-01',
        'land_area' => 100,
        'building_area' => 0,
        'category' => 'standar',
        'base_price' => 50000000,
        'final_selling_price' => 50000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    // 1. Dashboard
    Livewire::test(\App\Livewire\Dashboard::class)->assertStatus(200);

    // 2. Bookings
    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->set('search', 'Audit')
        ->set('datePeriod', 'this_month')
        ->set('statusFilter', 'all')
        ->assertStatus(200);

    // 3. Daily Activity Reports
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->set('search', 'Survey')
        ->set('datePeriod', 'today')
        ->assertStatus(200);

    // 4. Documents
    Livewire::test(\App\Livewire\Documents\Index::class)
        ->set('search', 'SPP')
        ->set('datePeriod', 'this_year')
        ->assertStatus(200);

    // 5. Payables
    Livewire::test(\App\Livewire\Payables\Index::class)
        ->set('search', 'Toko')
        ->set('datePeriod', 'last_month')
        ->assertStatus(200);

    // 6. Manual Invoices
    Livewire::test(\App\Livewire\ManualInvoices\Index::class)
        ->set('search', 'INV')
        ->set('datePeriod', 'this_week')
        ->assertStatus(200);

    // 7. Employee Salaries
    Livewire::test(\App\Livewire\EmployeeSalaries\Index::class)
        ->set('activeTab', 'payments')
        ->set('datePeriod', 'this_month')
        ->assertStatus(200);

    // 8. Proposals
    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->set('search', 'AUDIT-01')
        ->set('datePeriod', 'today')
        ->set('statusFilter', 'menunggu')
        ->assertStatus(200);

    // 9. Activity Logs
    Livewire::test(\App\Livewire\ActivityLogs\Index::class)
        ->set('search', 'Founder')
        ->set('datePeriod', 'today')
        ->set('activeTab', 'database')
        ->assertStatus(200);

    // 10. Field Expenses
    Livewire::test(\App\Livewire\FieldExpenses\Index::class)
        ->set('search', 'Semen')
        ->set('datePeriod', 'this_month')
        ->assertStatus(200);

    // 11. Projects Index & Show
    Livewire::test(\App\Livewire\Projects\Index::class)
        ->set('search', 'Proyek')
        ->assertStatus(200);
    Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $project->id])
        ->assertStatus(200);

    // 12. Units Index & Show
    Livewire::test(\App\Livewire\Units\Index::class)
        ->set('search', 'AUDIT')
        ->assertStatus(200);
    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->assertStatus(200);

    // 13. Workers
    Livewire::test(\App\Livewire\Workers\Index::class)
        ->set('search', 'Mandor')
        ->assertStatus(200);

    // 14. Cashflow
    Livewire::test(\App\Livewire\Cashflow\Index::class)
        ->set('search', 'Kas')
        ->assertStatus(200);

    // 15. Installments
    Livewire::test(\App\Livewire\Installments\Index::class)
        ->set('search', 'Cicilan')
        ->assertStatus(200);
});
