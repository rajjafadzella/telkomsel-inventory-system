<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Masukkan Data Role Wajib (Admin, Staff, Manager)
        $adminRole = Role::create(['name' => 'Admin']);
        $staffRole = Role::create(['name' => 'Staff']);
        $managerRole = Role::create(['name' => 'Manager']);

        // 2. Masukkan Akun Testing untuk Masing-Masing Role
        User::create([
            'name' => 'Admin Telkomsel',
            'email' => 'admin@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Staff Inventaris',
            'email' => 'staff@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => $staffRole->id,
        ]);

        User::create([
            'name' => 'Manager Telkomsel',
            'email' => 'manager@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => $managerRole->id,
        ]);
    }
}