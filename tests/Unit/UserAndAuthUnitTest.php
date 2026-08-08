<?php

use App\Models\User;

test('user model role helpers evaluate roles accurately', function () {
    $founder = User::factory()->create(['role' => 'founder', 'is_active' => true]);
    $supervisor = User::factory()->create(['role' => 'supervisor', 'is_active' => true]);
    $pengawas = User::factory()->create(['role' => 'pengawas_project', 'is_active' => true]);
    $finance = User::factory()->create(['role' => 'finance', 'is_active' => true]);
    $marketing = User::factory()->create(['role' => 'marketing', 'is_active' => true]);
    $inactive = User::factory()->create(['role' => 'marketing', 'is_active' => false]);

    expect($founder->isFounder())->toBeTrue();
    expect($founder->isSupervisor())->toBeFalse();

    expect($supervisor->isSupervisor())->toBeTrue();
    expect($pengawas->isPengawasProject())->toBeTrue();
    expect($finance->isFinance())->toBeTrue();
    expect($marketing->isMarketing())->toBeTrue();

    expect((bool)$founder->is_active)->toBeTrue();
    expect((bool)$inactive->is_active)->toBeFalse();
});
