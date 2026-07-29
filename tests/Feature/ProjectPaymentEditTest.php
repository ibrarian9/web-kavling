<?php

use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Livewire\Projects\Show as ProjectsShow;
use Livewire\Livewire;

test('founder and finance can call editProjectPayment on Projects Show Livewire component', function () {
    Role::findOrCreate('founder', 'web');

    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_test@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Permata Land',
        'location' => 'Jalan Permata',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 5000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Test payment',
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(ProjectsShow::class, ['id' => $project->id])
        ->call('editProjectPayment', $payment->id)
        ->assertSet('editingPaymentId', $payment->id)
        ->assertSet('payment_amount', 5000000)
        ->assertSet('showPaymentModal', true);
});
