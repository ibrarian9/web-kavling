<?php

namespace Tests\Feature;

use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRoleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pengawasUser;
    protected Project $project;
    protected Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'pengawas_project', 'guard_name' => 'web']);

        $this->admin = User::create([
            'name' => 'Admin Utama Real Estate',
            'email' => 'admin_crud@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');

        $this->pengawasUser = User::create([
            'name' => 'Pengawas Lapangan Budi',
            'email' => 'pengawas_crud@test.com',
            'password' => bcrypt('password'),
            'role' => 'pengawas_project',
            'is_active' => true,
        ]);
        $this->pengawasUser->assignRole('pengawas_project');

        $this->project = Project::create([
            'name' => 'Cluster Admin Emerald',
            'location' => 'Pekanbaru Center',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 1000000,
            'base_price' => 200000000,
            'total_project_price' => 500000000,
            'status' => 'aktif',
            'created_by' => $this->admin->id,
        ]);

        $this->unit = Unit::create([
            'project_id' => $this->project->id,
            'code' => 'EM-01',
            'category' => 'kavling',
            'type' => 'kavling',
            'land_width' => 10,
            'land_length' => 10,
            'land_area' => 100,
            'hpp' => 200000000,
            'status' => 'tersedia',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_perform_full_crud_on_projects(): void
    {
        $this->actingAs($this->admin);

        // 1. READ Projects List
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->assertStatus(200)
            ->assertSee('Cluster Admin Emerald');

        // 2. CREATE Project
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('openModal')
            ->set('name', 'Cluster Admin Sapphire')
            ->set('location', 'Panam Pekanbaru')
            ->set('standard_land_area', 120)
            ->set('excess_price_per_sqm', 1200000)
            ->set('base_price', 250000000)
            ->set('total_project_price', 600000000)
            ->call('saveProject')
            ->assertHasNoErrors();

        $newProject = Project::where('name', 'Cluster Admin Sapphire')->first();
        $this->assertNotNull($newProject);

        // 3. UPDATE Project
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('editProject', $this->project->id)
            ->set('name', 'Cluster Admin Emerald VIP')
            ->call('saveProject')
            ->assertHasNoErrors();

        $this->assertEquals('Cluster Admin Emerald VIP', $this->project->fresh()->name);

        // 4. ASSIGN & REMOVE Pengawas Project
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('openWorkerModal', $this->project->id)
            ->set('assign_user_id', $this->pengawasUser->id)
            ->set('assigned_role', 'Pengawas Utama Proyek')
            ->call('saveWorkerAssignment')
            ->assertHasNoErrors();

        $assignment = WorkerAssignment::where('user_id', $this->pengawasUser->id)->where('project_id', $this->project->id)->first();
        $this->assertNotNull($assignment);

        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('removePengawasAssignment', $assignment->id)
            ->assertHasNoErrors();

        $this->assertNull(WorkerAssignment::find($assignment->id));
    }

    public function test_admin_can_perform_full_crud_on_units(): void
    {
        $this->actingAs($this->admin);

        // 1. READ Units List
        Livewire::test(\App\Livewire\Units\Index::class)
            ->assertStatus(200)
            ->assertSee('EM-01');

        // 2. CREATE Unit
        Livewire::test(\App\Livewire\Units\Index::class)
            ->set('selected_project_id', $this->project->id)
            ->set('code', 'EM-02')
            ->set('category', 'rumah')
            ->set('building_area', 45)
            ->set('floors_count', 1)
            ->set('land_width', 10)
            ->set('land_length', 12)
            ->set('land_area', 120)
            ->call('saveUnit')
            ->assertHasNoErrors();

        $newUnit = Unit::where('code', 'EM-02')->first();
        $this->assertNotNull($newUnit);
        $this->assertEquals('rumah', $newUnit->category);

        // 3. UPDATE Unit Specifications in Unit Detail
        Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->call('openEditUnitModal')
            ->set('edit_unit_code', 'EM-01-VIP')
            ->set('edit_unit_category', 'kavling')
            ->set('edit_unit_status', 'tersedia')
            ->set('edit_land_area', 110)
            ->call('saveEditUnit')
            ->assertHasNoErrors();

        $this->assertEquals('EM-01-VIP', $this->unit->fresh()->code);
    }

    public function test_admin_can_perform_full_crud_on_workers(): void
    {
        $this->actingAs($this->admin);

        // 1. READ Workers List
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->assertStatus(200);

        // 2. CREATE Worker
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->call('create')
            ->set('name', 'Mandor Santoso')
            ->set('phone', '081234567890')
            ->set('address', 'Jl. Merdeka')
            ->set('type', 'mandor')
            ->set('specialty', 'Struktur & Pondasi')
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors();

        $worker = Worker::where('name', 'Mandor Santoso')->first();
        $this->assertNotNull($worker);

        // 3. UPDATE Worker
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->call('edit', $worker->id)
            ->set('name', 'Mandor Santoso S.T.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Mandor Santoso S.T.', $worker->fresh()->name);

        // 4. TOGGLE Status Worker
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->call('toggleStatus', $worker->id);

        $this->assertEquals('inactive', $worker->fresh()->status);

        // 5. ASSIGN Worker to Project & Unit
        Livewire::test(\App\Livewire\Workers\Index::class)
            ->call('openAssignModal', $worker->id)
            ->set('assignProjectId', $this->project->id)
            ->set('assignUnitId', $this->unit->id)
            ->set('assignedRole', 'Mandor Utama Proyek')
            ->call('saveAssignment')
            ->assertHasNoErrors();

        $assignment = WorkerAssignment::where('worker_id', $worker->id)->where('project_id', $this->project->id)->first();
        $this->assertNotNull($assignment);
    }

    public function test_admin_can_manage_installment_schemes_and_payments_for_units(): void
    {
        $this->actingAs($this->admin);

        // 1. CREATE Installment Scheme
        Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->call('openSetupInstallmentModal')
            ->set('setup_total_price', 250000000)
            ->set('setup_down_payment', 50000000)
            ->set('setup_installment_count', 20)
            ->set('setup_installment_amount', 10000000)
            ->set('setup_start_date', now()->toDateString())
            ->call('saveSetupInstallment')
            ->assertHasNoErrors();

        $installment = UnitInstallment::where('unit_id', $this->unit->id)->first();
        $this->assertNotNull($installment);
        $this->assertEquals(250000000, $installment->total_price);

        // 2. INPUT Setoran Cicilan
        Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->call('openInstallmentPaymentModal')
            ->set('installment_payment_amount', 10000000)
            ->set('installment_payment_method', 'Transfer Bank BCA')
            ->set('installment_payment_date', now()->toDateString())
            ->set('installment_payment_notes', 'Setoran cicilan ke-1')
            ->call('saveInstallmentPayment')
            ->assertHasNoErrors();

        $payment = InstallmentPayment::where('unit_installment_id', $installment->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(10000000, $payment->amount_paid);

        // 3. EDIT Setoran Cicilan
        Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->call('editInstallmentPayment', $payment->id)
            ->set('installment_payment_amount', 12000000)
            ->call('saveInstallmentPayment')
            ->assertHasNoErrors();

        $this->assertEquals(12000000, $payment->fresh()->amount_paid);
    }
}
