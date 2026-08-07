<?php

namespace Tests\Feature;

use App\Livewire\GlobalSearch;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_search_globally_without_sql_errors(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $unit = Unit::firstOrFail();
        $project = Project::firstOrFail();

        Livewire::actingAs($founder)
            ->test(GlobalSearch::class)
            ->set('isOpen', true)
            ->set('query', $unit->code)
            ->assertSee($unit->code)
            ->set('query', $project->name)
            ->assertSee($project->name)
            ->set('query', 'Dashboard')
            ->assertSee('Dashboard Utama');
    }
}
