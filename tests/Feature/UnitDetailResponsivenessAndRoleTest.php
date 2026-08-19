<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkerAssignment;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitDetailResponsivenessAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_marketing_finance_and_founder_can_see_spp_and_proposals_on_unit_detail(): void
    {
        $unit = Unit::firstOrFail();
        $marketing = User::where('role', 'marketing')->firstOrFail();
        $finance = User::where('role', 'finance')->firstOrFail();
        $founder = User::where('role', 'founder')->firstOrFail();

        // Check Marketing
        $this->actingAs($marketing)
            ->get(route('units.show', $unit->id))
            ->assertStatus(200)
            ->assertSee('Surat Pesanan Penjualan (SPP)');

        // Check Finance
        $this->actingAs($finance)
            ->get(route('units.show', $unit->id))
            ->assertStatus(200)
            ->assertSee('Surat Pesanan Penjualan (SPP)');

        // Check Founder
        $this->actingAs($founder)
            ->get(route('units.show', $unit->id))
            ->assertStatus(200)
            ->assertSee('Surat Pesanan Penjualan (SPP)');
    }

    public function test_pengawas_project_cannot_see_spp_proposals_or_installments_on_unit_detail(): void
    {
        $unit = Unit::firstOrFail();
        $pengawas = User::where('role', 'pengawas_project')->firstOrFail();

        // Assign pengawas to project
        WorkerAssignment::updateOrCreate(
            ['user_id' => $pengawas->id, 'project_id' => $unit->project_id],
            ['assigned_role' => 'Pengawas Lapangan', 'status' => 'active', 'start_date' => now()->toDateString()]
        );

        $this->actingAs($pengawas)
            ->get(route('units.show', $unit->id))
            ->assertStatus(200)
            ->assertDontSee('Surat Pesanan Penjualan (SPP)')
            ->assertDontSee('Skema Cicilan &amp; Pembayaran Pembeli', false)
            ->assertDontSee('Total Kas Masuk (DP + Cicilan)');
    }

    public function test_infrastructure_unit_detail_hides_booking_spp_commissions_and_buyer_installments(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $project = Project::firstOrFail();

        $infraUnit = Unit::create([
            'project_id' => $project->id,
            'code' => 'INFRA-01',
            'category' => 'infrastruktur',
            'type' => 'Jalan & Drainase',
            'land_width' => 10,
            'land_length' => 50,
            'land_area' => 500,
            'hpp' => 50000000,
            'status' => 'infrastruktur',
            'created_by' => $founder->id,
        ]);

        $response = $this->actingAs($founder)->get(route('units.show', $infraUnit->id));

        $response->assertStatus(200)
            ->assertSee('UNIT INFRA-01')
            ->assertSee('Infrastruktur Proyek')
            ->assertSee('Realisasi Biaya Lapangan')
            ->assertDontSee('Booking Fee / Tanda Jadi')
            ->assertDontSee('Total Setoran Pembeli')
            ->assertDontSee('Harga Jual Disetujui')
            ->assertDontSee('Surat Pesanan Penjualan (SPP)')
            ->assertDontSee('Skema Cicilan &amp; Pembayaran Pembeli', false)
            ->assertDontSee('Komisi Penjual Unit');
    }

    public function test_all_main_application_routes_return_200_ok(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $unit = Unit::firstOrFail();
        $project = Project::firstOrFail();

        $routes = [
            route('dashboard'),
            route('projects.index'),
            route('projects.show', $project->id),
            route('units.index'),
            route('units.show', $unit->id),
            route('workers.index'),
            route('field-expenses.index'),
            route('bookings.index'),
            route('proposals.index'),
            route('documents.index'),
            route('installments.index'),
            route('cashflow.index'),
            route('activity-logs.index'),
            route('users.index'),
        ];

        foreach ($routes as $url) {
            $response = $this->actingAs($founder)->get($url);
            $response->assertStatus(200);
        }
    }
}
