<?php

use App\Livewire\Bookings\Index as BookingsIndex;
use App\Livewire\Installments\Index as InstallmentsIndex;
use App\Livewire\ManualInvoices\Index as ManualInvoicesIndex;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Units\Index as UnitsIndex;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Livewire\Livewire;

test('system logs activities on project creation, unit creation, booking, and manual invoice creation', function () {
    $founder = User::factory()->create(['role' => 'founder', 'name' => 'Founder Audit Test']);
    $this->actingAs($founder);

    // 1. Create Project -> PROJECT_CREATED
    Livewire::test(ProjectsIndex::class)
        ->set('name', 'Proyek Audit Test')
        ->set('location', 'Pekanbaru')
        ->set('standard_land_area', 100)
        ->set('excess_price_per_sqm', 1000000)
        ->set('base_price', 150000000)
        ->call('saveProject')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'PROJECT_CREATED',
    ]);

    $project = Project::where('name', 'Proyek Audit Test')->firstOrFail();

    // 2. Create Unit -> UNIT_CREATED
    Livewire::test(UnitsIndex::class)
        ->set('selected_project_id', $project->id)
        ->set('code', 'AUD-01')
        ->set('category', 'kavling')
        ->set('land_width', 10)
        ->set('land_length', 10)
        ->set('land_area', 100)
        ->call('saveUnit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'UNIT_CREATED',
    ]);

    $unit = Unit::where('code', 'AUD-01')->firstOrFail();

    // 3. Create Booking -> BOOKING_CREATED
    Livewire::test(BookingsIndex::class)
        ->set('project_id', $project->id)
        ->set('unit_id', $unit->id)
        ->set('buyer_name', 'Konsumen Audit Test')
        ->set('buyer_phone', '08123456789')
        ->set('booking_amount', 5000000)
        ->set('booking_date', now()->toDateString())
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'BOOKING_CREATED',
    ]);

    // 4. Create Manual Invoice -> MANUAL_INVOICE_CREATED
    Livewire::test(ManualInvoicesIndex::class)
        ->set('recipient_name', 'Toko Bangunan Audit')
        ->set('amount', 2500000)
        ->set('type', 'masuk')
        ->set('category', 'penjualan_unit')
        ->set('invoice_date', now()->toDateString())
        ->set('payment_method', 'Transfer Bank')
        ->set('status', 'lunas')
        ->call('saveInvoice')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $founder->id,
        'action' => 'MANUAL_INVOICE_CREATED',
    ]);
});
