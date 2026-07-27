<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkerAssignment;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_founder_can_access_all_main_pages(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $project = Project::firstOrFail();
        $unit = Unit::firstOrFail();

        $this->actingAs($founder);

        $routes = [
            '/dashboard',
            '/projects',
            '/projects/' . $project->id,
            '/units',
            '/units/' . $unit->id,
            '/bookings',
            '/proposals',
            '/installments',
            '/cashflow',
            '/field-expenses',
            '/workers',
            '/activity-logs',
            '/documents',
            '/users',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_pengawas_can_access_assigned_projects_and_units(): void
    {
        $pengawas = User::where('role', 'pengawas_project')->firstOrFail();
        $project = Project::firstOrFail();
        $unit = Unit::firstOrFail();

        // Assign pengawas to project
        WorkerAssignment::updateOrCreate(
            ['user_id' => $pengawas->id, 'project_id' => $project->id],
            ['assigned_role' => 'Pengawas Lapangan', 'status' => 'active', 'start_date' => now()->toDateString()]
        );

        $this->actingAs($pengawas);

        $response = $this->get('/projects');
        $response->assertStatus(200);

        $response = $this->get('/projects/' . $project->id);
        $response->assertStatus(200);

        $response = $this->get('/units');
        $response->assertStatus(200);

        $response = $this->get('/units/' . $unit->id);
        $response->assertStatus(200);
    }
}
