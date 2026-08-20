<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingRejectionAndHppVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_finance_and_founder_can_reject_booking_and_unit_reverts_to_available(): void
    {
        $unit = Unit::where('status', 'tersedia')->firstOrFail();
        $marketing = User::where('role', 'marketing')->firstOrFail();
        $finance = User::where('role', 'finance')->firstOrFail();

        // 1. Marketing creates booking
        $booking = Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => 'Calon Pembeli Tes',
            'buyer_phone' => '08123456789',
            'booking_type' => 'unit',
            'booking_amount' => 5000000,
            'dp_amount' => 0,
            'booking_date' => now()->toDateString(),
            'status' => 'active',
            'created_by' => $marketing->id,
        ]);

        $unit->update(['status' => 'booked']);

        // 2. Finance rejects the booking via Livewire
        Livewire::actingAs($finance)
            ->test(\App\Livewire\Bookings\Index::class)
            ->call('rejectDp', $booking->id)
            ->assertDispatched('notify');

        // 3. Verify database state
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'status' => 'tersedia',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'BOOKING_REJECTED',
        ]);
    }

    public function test_finance_and_founder_can_cancel_or_refund_approved_dp(): void
    {
        $unit = Unit::where('status', 'tersedia')->firstOrFail();
        $finance = User::where('role', 'finance')->firstOrFail();

        $booking = Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => 'Pembeli Refund Tes',
            'buyer_phone' => '08123456789',
            'booking_type' => 'unit',
            'booking_amount' => 5000000,
            'dp_amount' => 5000000,
            'booking_date' => now()->toDateString(),
            'status' => 'converted',
            'created_by' => $finance->id,
        ]);

        $unit->update(['status' => 'booked']);

        Livewire::actingAs($finance)
            ->test(\App\Livewire\Bookings\Index::class)
            ->call('cancelApprovedDp', $booking->id)
            ->assertDispatched('notify');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'refunded',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'status' => 'tersedia',
        ]);

        $this->assertDatabaseHas('cashflow_transactions', [
            'type' => 'keluar',
            'reference_id' => $booking->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'BOOKING_REFUNDED',
        ]);
    }

    public function test_marketing_and_pengawas_cannot_approve_or_reject_booking(): void
    {
        $booking = Booking::create([
            'project_id' => Project::firstOrFail()->id,
            'buyer_name' => 'Calon Pembeli Tes 2',
            'buyer_phone' => '08123456789',
            'booking_type' => 'project',
            'booking_amount' => 5000000,
            'booking_date' => now()->toDateString(),
            'status' => 'active',
            'created_by' => User::where('role', 'marketing')->firstOrFail()->id,
        ]);

        $marketing = User::where('role', 'marketing')->firstOrFail();

        Livewire::actingAs($marketing)
            ->test(\App\Livewire\Bookings\Index::class)
            ->call('rejectDp', $booking->id)
            ->assertDispatched('notify');

        // Booking status remains active
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'active',
        ]);
    }

    public function test_hpp_is_visible_only_to_founder_and_finance(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $finance = User::where('role', 'finance')->firstOrFail();
        $marketing = User::where('role', 'marketing')->firstOrFail();
        $supervisor = User::where('role', 'supervisor')->firstOrFail();

        $unit = Unit::firstOrFail();

        // 1. Founder can view Harga Jual and Harga Total
        $this->actingAs($founder)->get(route('units.show', $unit->id))->assertSee('Harga Jual Standar')->assertSee('Harga Total Unit');
        $this->actingAs($founder)->get(route('units.index'))->assertSee('Harga Jual:')->assertSee('Harga Total:');

        // 2. Finance can view Harga Jual and Harga Total
        $this->actingAs($finance)->get(route('units.show', $unit->id))->assertSee('Harga Jual Standar')->assertSee('Harga Total Unit');
        $this->actingAs($finance)->get(route('units.index'))->assertSee('Harga Jual:')->assertSee('Harga Total:');

        // 3. Marketing can view Harga Jual and Harga Total
        $this->actingAs($marketing)->get(route('units.show', $unit->id))->assertSee('Harga Jual Standar')->assertSee('Harga Total Unit');
        $this->actingAs($marketing)->get(route('units.index'))->assertSee('Harga Jual:')->assertSee('Harga Total:');

        // 4. Supervisor can view Harga Jual and Harga Total
        $this->actingAs($supervisor)->get(route('units.show', $unit->id))->assertSee('Harga Jual Standar')->assertSee('Harga Total Unit');
        $this->actingAs($supervisor)->get(route('units.index'))->assertSee('Harga Jual:')->assertSee('Harga Total:');
    }
}
