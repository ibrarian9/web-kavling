<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogsNotificationTabTest extends TestCase
{
    use RefreshDatabase;

    protected User $founder;
    protected User $admin;
    protected User $marketing;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);

        $this->founder = User::create([
            'name' => 'Founder Chief',
            'email' => 'founder_logs@test.com',
            'password' => bcrypt('password'),
            'role' => 'founder',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin Staff',
            'email' => 'admin_logs@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->marketing = User::create([
            'name' => 'Marketing Sales',
            'email' => 'marketing_logs@test.com',
            'password' => bcrypt('password'),
            'role' => 'marketing',
            'is_active' => true,
        ]);
    }

    public function test_non_founder_users_are_forbidden_from_accessing_activity_logs(): void
    {
        $this->actingAs($this->admin);
        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->assertStatus(403);

        $this->actingAs($this->marketing);
        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->assertStatus(403);
    }

    public function test_founder_can_view_activity_logs_with_separated_operational_and_notification_tabs(): void
    {
        // Seed operational logs
        ActivityLogger::log('UNIT_CREATED', 'Unit A-01 baru ditambahkan ke sistem.', $this->admin);
        ActivityLogger::log('CASHFLOW_CREATED', 'Pencatatan kas masuk Rp 10.000.000.', $this->founder);

        // Seed notification logs
        ActivityLogger::logNotification('PROPOSAL_SUBMITTED', 'Notifikasi pengajuan harga Unit B-05 terkirim ke Founder.', $this->marketing);
        ActivityLogger::logNotification('PAYMENT_RECEIVED', 'Notifikasi pembayaran DP Rp 5.000.000 terkirim ke Finance.', $this->admin);

        $this->actingAs($this->founder);

        // Test Default Tab ('database' / Operational & Financial)
        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->assertStatus(200)
            ->assertSet('activeTab', 'database')
            ->assertSee('Unit A-01 baru ditambahkan')
            ->assertSee('Pencatatan kas masuk')
            ->assertDontSee('Notifikasi pengajuan harga Unit B-05');

        // Switch to 'notifications' Tab
        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->call('setTab', 'notifications')
            ->assertStatus(200)
            ->assertSet('activeTab', 'notifications')
            ->assertSee('Notifikasi pengajuan harga Unit B-05')
            ->assertSee('Notifikasi pembayaran DP')
            ->assertDontSee('Unit A-01 baru ditambahkan');
    }

    public function test_founder_can_clear_database_logs(): void
    {
        ActivityLogger::log('UNIT_UPDATED', 'Spesifikasi Unit C-01 diperbarui.', $this->admin);
        ActivityLogger::logNotification('WORKER_ASSIGNED', 'Notifikasi penugasan Pengawas Lapangan.', $this->founder);

        $this->assertEquals(2, ActivityLog::count());

        $this->actingAs($this->founder);

        Livewire::test(\App\Livewire\ActivityLogs\Index::class)
            ->call('clearDatabaseLogs');

        // Only the clear log itself remains in DB
        $this->assertEquals(1, ActivityLog::count());
        $this->assertEquals('SYSTEM_CLEAR_LOGS', ActivityLog::first()->action);
    }
}
