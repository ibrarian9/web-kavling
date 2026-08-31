<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ActivityLogsTabSwitchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_switch_tabs_between_database_notifications_and_file()
    {
        $founder = User::factory()->create([
            'role' => 'founder',
            'email' => 'founder@test.com',
        ]);

        ActivityLog::create([
            'user_id' => $founder->id,
            'user_name' => $founder->name,
            'user_role' => 'founder',
            'action' => 'AUTH_LOGIN',
            'description' => 'Founder login',
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::create([
            'user_id' => $founder->id,
            'user_name' => $founder->name,
            'user_role' => 'founder',
            'action' => 'NOTIF_WHATSAPP_SENT',
            'description' => 'Notification WhatsApp sent',
            'ip_address' => '127.0.0.1',
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ActivityLogs\Index::class)
            ->assertSee('Operasional', false)
            ->assertSee('Notifikasi Terkirim', false)
            ->assertSee('File Server', false)
            ->assertSee('AUTH_LOGIN')
            ->call('setTab', 'notifications')
            ->assertSet('activeTab', 'notifications')
            ->assertSee('NOTIF_WHATSAPP_SENT')
            ->call('setTab', 'file')
            ->assertSet('activeTab', 'file')
            ->assertSee('laravel.log')
            ->call('setTab', 'database')
            ->assertSet('activeTab', 'database')
            ->assertSee('AUTH_LOGIN');
    }
}
