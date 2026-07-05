<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Masukkan Data Role Wajib (Admin, Staff, Manager)
        $adminRole   = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole   = Role::firstOrCreate(['name' => 'Staff']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);

        Category::firstOrCreate(['name' => 'Elektronik']);
        Category::firstOrCreate(['name' => 'Aset Kantor']);
        Category::firstOrCreate(['name' => 'Alat Tulis Kantor (ATK)']);

        // 2. Masukkan Akun Testing untuk Masing-Masing Role
        User::firstOrCreate(
            ['email' => 'admin@telkomsel.com'],
            [
                'name'     => 'Admin Telkomsel',
                'password' => Hash::make('password123'),
                'role_id'  => $adminRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@telkomsel.com'],
            [
                'name'     => 'Staff Inventaris',
                'password' => Hash::make('password123'),
                'role_id'  => $staffRole->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@telkomsel.com'],
            [
                'name'     => 'Manager Telkomsel',
                'password' => Hash::make('password123'),
                'role_id'  => $managerRole->id,
            ]
        );
    }
}