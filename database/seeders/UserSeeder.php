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
                'name' => 'Marwansyah',
                'email' => 'marwansyah@simatlantik.my.id',
                'role' => 'founder',
                'password' => 'password123'
            ],
            [
                'name' => 'Yannoki',
                'email' => 'Yannoki12@gmail.com',
                'role' => 'pengawas_project',
                'password' => '150594'
            ],
            [
                'name' => 'Ir. H. Ahmad Syafrizal (Field Supervisor)',
                'email' => 'supervisor@kavling.com',
                'role' => 'supervisor',
                'password' => 'password123'
            ],
            [
                'name' => 'Ibu Wan Rahmah, S.E., M.Si. (Chief Finance)',
                'email' => 'finance@kavling.com',
                'role' => 'finance',
                'password' => 'password123'
            ],
            [
                'name' => 'Rian Gunawan, S.Kom. (Marketing Executive)',
                'email' => 'marketing@kavling.com',
                'role' => 'marketing',
                'password' => 'password123'
            ],
            [
                'name' => 'Bambang Syafruddin, A.Md. (Pengawas Lapangan 2)',
                'email' => 'pengawas2@kavling.com',
                'role' => 'pengawas_project',
                'password' => 'password123'
            ],
        ];

        foreach ($usersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'is_active' => true,
                ]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
