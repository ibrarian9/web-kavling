<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roles = ['founder', 'pengawas_project', 'supervisor', 'finance', 'marketing'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Production Users (Pekanbaru, Riau Team)
        $usersData = [
            [
                'name' => 'H. Tengku Zulkarnain, S.E. (Founder)',
                'email' => 'founder@kavling.com',
                'role' => 'founder',
            ],
            [
                'name' => 'Zulham Efendi, S.T. (Pengawas Lapangan 1)',
                'email' => 'pengawas@kavling.com',
                'role' => 'pengawas_project',
            ],
            [
                'name' => 'Ir. H. Ahmad Syafrizal (Field Supervisor)',
                'email' => 'supervisor@kavling.com',
                'role' => 'supervisor',
            ],
            [
                'name' => 'Ibu Wan Rahmah, S.E., M.Si. (Chief Finance)',
                'email' => 'finance@kavling.com',
                'role' => 'finance',
            ],
            [
                'name' => 'Rian Gunawan, S.Kom. (Marketing Executive)',
                'email' => 'marketing@kavling.com',
                'role' => 'marketing',
            ],
            [
                'name' => 'Devi Permata Melayu (Marketing)',
                'email' => 'marketing2@kavling.com',
                'role' => 'marketing',
            ],
            [
                'name' => 'Rizky Kurniawan, S.M. (Marketing Senior)',
                'email' => 'marketing3@kavling.com',
                'role' => 'marketing',
            ],
            [
                'name' => 'Bambang Syafruddin, A.Md. (Pengawas Lapangan 2)',
                'email' => 'pengawas2@kavling.com',
                'role' => 'pengawas_project',
            ],
        ];

        foreach ($usersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => $data['role'],
                    'is_active' => true,
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
