<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_cannot_access_activity_logs_page(): void
    {
        $response = $this->get('/activity-logs');
        $response->assertRedirect('/login');
    }

    public function test_non_founder_cannot_access_activity_logs_page(): void
    {
        $marketing = User::where('role', 'marketing')->firstOrFail();

        $response = $this->actingAs($marketing)->get('/activity-logs');
        $response->assertStatus(403);
    }

    public function test_founder_can_access_activity_logs_page(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $response = $this->actingAs($founder)->get('/activity-logs');
        $response->assertStatus(200);
        $response->assertSee('Audit Trail System');
    }

    public function test_activity_logger_service_creates_database_record_for_user(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        ActivityLogger::log('TEST_EVENT', 'Testing activity logger service', $founder);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $founder->id,
            'user_name' => $founder->name,
            'user_role' => $founder->role,
            'action' => 'TEST_EVENT',
            'description' => 'Testing activity logger service',
        ]);
    }

    public function test_activity_logger_service_creates_database_record_for_guest(): void
    {
        ActivityLogger::log('SYSTEM_TEST', 'Testing system log without user');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => null,
            'user_name' => 'Guest/System',
            'user_role' => 'System',
            'action' => 'SYSTEM_TEST',
            'description' => 'Testing system log without user',
        ]);
    }

    public function test_user_login_triggers_activity_log(): void
    {
        $this->flushSession();
        $user = User::factory()->create([
            'role' => 'founder',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => 'AUTH_LOGIN',
        ]);
    }

    public function test_role_switch_triggers_activity_log(): void
    {
        $founder = User::factory()->create(['role' => 'founder', 'is_active' => true]);
        User::factory()->create(['role' => 'marketing', 'is_active' => true]);

        $response = $this->actingAs($founder)->get('/switch-role/marketing');
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'AUTH_SWITCH_ROLE',
        ]);
    }

    public function test_livewire_activity_logs_index_component_renders_and_filters(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        ActivityLog::create([
            'user_id' => $founder->id,
            'user_name' => $founder->name,
            'user_role' => $founder->role,
            'action' => 'UNIQUE_SEARCH_ACTION',
            'description' => 'Special test description for filter test',
            'ip_address' => '127.0.0.1',
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ActivityLogs\Index::class)
            ->assertStatus(200)
            ->set('search', 'UNIQUE_SEARCH_ACTION')
            ->assertSee('Special test description for filter test')
            ->set('search', 'NON_EXISTENT_LOG_KEYWORD')
            ->assertDontSee('Special test description for filter test');
    }

    public function test_livewire_founder_can_clear_database_logs(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        ActivityLog::create([
            'user_id' => $founder->id,
            'user_name' => $founder->name,
            'user_role' => $founder->role,
            'action' => 'OLD_ACTION',
            'description' => 'Old activity log before clear',
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ActivityLogs\Index::class)
            ->call('clearDatabaseLogs')
            ->assertStatus(200);

        // Old action should be wiped
        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'OLD_ACTION',
        ]);

        // The system clear log itself should be recorded
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'SYSTEM_CLEAR_LOGS',
        ]);
    }

    public function test_livewire_founder_can_clear_file_log(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ActivityLogs\Index::class)
            ->call('clearFileLog')
            ->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'SYSTEM_CLEAR_FILE_LOG',
        ]);
    }
}
