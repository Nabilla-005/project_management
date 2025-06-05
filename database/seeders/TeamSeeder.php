<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $pmRole = Role::firstOrCreate(['name' => 'Project Manager']);
        $tmRole = Role::firstOrCreate(['name' => 'Team Member']);

        // Buat user Sintia (backend)
        $sintia = User::create([
            'name' => 'Sintia Wati',
            'email' => 'sintia@gmail.com',
            'password' => Hash::make('password'), // ganti kalau perlu
        ]);
        $sintia->assignRole($adminRole);

        // Buat user Nabilla (frontend)
        $nabilla = User::create([
            'name' => 'Nabilla Hasya Permana',
            'email' => 'nabilla@gmail.com',
            'password' => Hash::make('password'), // ganti kalau perlu
        ]);
        $nabilla->assignRole($pmRole);

        // Buat user Team ()
        $timUser = User::create([
            'name' => 'Sina',
            'email' => 'sina@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $timUser->assignRole($tmRole);

        // Buat satu proyek nyata
        $project = Project::create([
            'name' => 'Sistem Manajemen Proyek',
            'description' => 'Website manajemen proyek dengan fitur autentikasi, manajemen tugas, komentar, dashboard, dll.',
            'start_date' => now()->subDays(7),
            'end_date' => now()->addDays(14),
            'created_by' => $sintia->id, 
        ]);

        // Tambahkan tugas-tugas real sesuai pembagian kelompok
        $tasks = [
            [
                'title' => 'Setup Laravel, JWT, Spatie Role-Permission',
                'description' => 'Konfigurasi awal backend menggunakan Laravel, JWT dan role-permission.',
                'status' => 'Done',
                'assigned_to' => $sintia->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Membuat Endpoint CRUD Proyek & Tugas',
                'description' => 'Backend untuk fitur proyek dan tugas (create, read, update, delete).',
                'status' => 'Done',
                'assigned_to' => $sintia->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Setup Vue + Tailwind + Routing + Auth',
                'description' => 'Konfigurasi awal frontend menggunakan Vue, Tailwind, dan autentikasi dengan JWT.',
                'status' => 'Done',
                'assigned_to' => $nabilla->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Dashboard Statistik dengan ChartJS',
                'description' => 'Menampilkan jumlah proyek, tugas, dan progress menggunakan ChartJS.',
                'status' => 'Done',
                'assigned_to' => $nabilla->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Notifikasi Realtime Laravel Echo + Pusher',
                'description' => 'Setup notifikasi realtime saat komentar atau status tugas berubah.',
                'status' => 'Done',
                'assigned_to' => $sintia->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Kanban Board (Drag & Drop Task)',
                'description' => 'Implementasi task board ala Trello menggunakan drag and drop.',
                'status' => 'Done',
                'assigned_to' => $nabilla->id,
                'created_by' => $nabilla->id,
            ],
            [
                'title' => 'Deploy Fullstack ke Vercel & Render',
                'description' => 'Deploy frontend ke Vercel dan backend ke Render gratis.',
                'status' => 'Done',
                'assigned_to' => $sintia->id,
                'created_by' => $nabilla->id,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create([
                'project_id' => $project->id,
                'title' => $task['title'],
                'description' => $task['description'],
                'status' => $task['status'],
                'assigned_to' => $task['assigned_to'],
                'created_by' => $task['created_by'],
            ]);
        }
    }
}