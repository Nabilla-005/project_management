<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting: Role & Permission dulu
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            TeamSeeder::class, // Seeder anggota kelompok: Sintia & Nabilla
        ]);
    }
}