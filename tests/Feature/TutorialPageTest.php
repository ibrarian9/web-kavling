<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('marketing', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('supervisor', 'web');
});

test('authenticated user can access Tutorial page and switch between all 8 tabs and roles', function () {
    $user = User::create([
        'name' => 'Founder Tutorial',
        'email' => 'founder_tutorial@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $user->assignRole('founder');

    $response = $this->actingAs($user)->get(route('tutorial.index'));

    $response->assertStatus(200);
    $response->assertSee('Pusat Tutorial');

    $component = Livewire::actingAs($user)
        ->test(\App\Livewire\Tutorial\Index::class)
        ->assertSet('viewMode', 'founder')
        ->assertSet('activeTab', 'master_unit')
        ->assertSee('Panduan Pengelolaan Proyek')
        
        // Tab 2: Booking
        ->call('setTab', 'booking')
        ->assertSet('activeTab', 'booking')
        ->assertSee('Panduan Booking Fee')
        
        // Tab 3: Proposals
        ->call('setTab', 'proposals')
        ->assertSet('activeTab', 'proposals')
        ->assertSee('Panduan Pengajuan')
        
        // Tab 4: Cash & Dokumen
        ->call('setTab', 'cash_dokumen')
        ->assertSet('activeTab', 'cash_dokumen')
        ->assertSee('Panduan Pembelian Cash')
        
        // Tab 5: Cicilan
        ->call('setTab', 'cicilan')
        ->assertSet('activeTab', 'cicilan')
        ->assertSee('Panduan Skema Pembelian Cicilan')
        
        // Tab 6: Operasional
        ->call('setTab', 'operasional')
        ->assertSet('activeTab', 'operasional')
        ->assertSee('Panduan Belanja Material')
        
        // Tab 7: Keuangan
        ->call('setTab', 'keuangan')
        ->assertSet('activeTab', 'keuangan')
        ->assertSee('Panduan Arus Kas')
        
        // Tab 8: FAQ
        ->call('setTab', 'faq')
        ->assertSet('activeTab', 'faq')
        ->assertSee('Tanya Jawab Seputar Operasional')
        
        // Switch Role Modes
        ->call('setViewMode', 'marketing')
        ->assertSet('viewMode', 'marketing')
        ->call('setViewMode', 'finance')
        ->assertSet('viewMode', 'finance')
        ->call('setViewMode', 'supervisor_pengawas')
        ->assertSet('viewMode', 'supervisor_pengawas')
        ->call('setViewMode', 'all')
        ->assertSet('viewMode', 'all');
});
