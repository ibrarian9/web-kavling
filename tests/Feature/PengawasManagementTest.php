<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\WorkerAssignment;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PengawasManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_founder_can_assign_remove_and_move_pengawas_project(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $pengawas = User::where('role', 'pengawas_project')->firstOrFail();
        
        WorkerAssignment::where('user_id', $pengawas->id)->delete();

        $projects = Project::take(2)->get();
        $projectA = $projects[0];
        $projectB = $projects[1];

        // 1. Founder assigns Pengawas to Project A
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Index::class)
            ->call('openWorkerModal', $projectA->id)
            ->set('assign_user_id', $pengawas->id)
            ->set('assigned_role', 'Pengawas Proyek A')
            ->call('saveWorkerAssignment')
            ->assertHasNoErrors();

        $assignment = WorkerAssignment::where('user_id', $pengawas->id)
            ->where('project_id', $projectA->id)
            ->where('status', 'active')
            ->first();

        $this->assertNotNull($assignment);

        // 2. Founder moves Pengawas to Project B
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Index::class)
            ->call('movePengawasAssignment', $assignment->id, $projectB->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('worker_assignments', [
            'id' => $assignment->id,
            'user_id' => $pengawas->id,
            'project_id' => $projectB->id,
            'status' => 'active',
        ]);

        // 3. Founder removes (copot) Pengawas from Project B
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Index::class)
            ->call('removePengawasAssignment', $assignment->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('worker_assignments', [
            'id' => $assignment->id,
        ]);
    }

    public function test_pengawas_only_sees_assigned_projects_and_hpp_hidden(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $pengawas = User::where('role', 'pengawas_project')->firstOrFail();
        $supervisor = User::where('role', 'supervisor')->firstOrFail();

        $this->assertTrue($founder->canViewHpp());
        $this->assertFalse($pengawas->canViewHpp());
        $this->assertTrue($supervisor->canViewHpp());

        $projects = Project::take(2)->get();
        $projectA = $projects[0];
        $projectB = $projects[1];

        // Assign pengawas to Project A only
        WorkerAssignment::where('user_id', $pengawas->id)->delete();
        WorkerAssignment::create([
            'user_id' => $pengawas->id,
            'project_id' => $projectA->id,
            'assigned_role' => 'Pengawas Project A',
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        // Pengawas project index only shows Project A
        Livewire::actingAs($pengawas)
            ->test(\App\Livewire\Projects\Index::class)
            ->assertSee($projectA->name)
            ->assertDontSee($projectB->name)
            ->assertDontSee('Harga Beli Lahan')
            ->assertDontSee('Harga Dasar Standar (HPP)');

        // Pengawas accessing unassigned project B detail gets 403
        $this->actingAs($pengawas)
            ->get(route('projects.show', $projectB->id))
            ->assertStatus(403);
    }

    public function test_projects_index_and_detail_only_display_pengawas_and_not_mandor_or_worker(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $pengawas = User::where('role', 'pengawas_project')->firstOrFail();

        $project = Project::create([
            'name' => 'Proyek Pengawas Only Test',
            'location' => 'Jl. Pengawas No. 1',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 1000000,
            'base_price' => 100000000,
            'total_project_price' => 500000000,
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        $worker = \App\Models\Worker::create([
            'name' => 'Mandor Borongan Rahasia',
            'type' => 'mandor',
            'specialty' => 'Struktur',
            'status' => 'active',
        ]);

        // 1. Assign Pengawas to project
        WorkerAssignment::create([
            'user_id' => $pengawas->id,
            'project_id' => $project->id,
            'assigned_role' => 'Pengawas Lapangan Proyek',
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        // 2. Assign Worker / Mandor to project
        WorkerAssignment::create([
            'worker_id' => $worker->id,
            'project_id' => $project->id,
            'assigned_role' => 'Mandor Struktur',
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        // Verify Projects Index: See Pengawas name, Don't see Mandor name in Pengawas Proyek column
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Index::class)
            ->assertSee('Pengawas Proyek')
            ->assertDontSee('Pekerja Lapangan')
            ->assertSee($pengawas->name)
            ->assertDontSee($worker->name);

        // Verify Projects Show Detail: See Pengawas name, Don't see Mandor name in Pengawas strip
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Show::class, ['id' => $project->id])
            ->assertSee('Pengawas Proyek')
            ->assertDontSee('Pengawas & Mandor Bertugas')
            ->assertSee($pengawas->name)
            ->assertDontSee($worker->name);
    }
}
