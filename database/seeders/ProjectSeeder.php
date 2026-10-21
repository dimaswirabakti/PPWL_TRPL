<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    // Tabel dikosongkan lebih dulu agar seeder aman dijalankan berulang kali
    public function run(): void
    {
        Project::truncate();

        $projects = [
            [
                'title' => 'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
                'description' => 'Aplikasi mobile untuk pemetaan sampah laut berbasis laporan warga, lengkap dengan peta sebaran dan statistik per wilayah.',
                'status' => 'published',
            ],
            [
                'title' => 'Website Reservasi Kos (KosKita)',
                'description' => 'Website untuk mencari dan memesan kos dengan filter harga, fasilitas, serta jarak ke kampus.',
                'status' => 'published',
            ],
            [
                'title' => 'Katalog Film (Movix)',
                'description' => 'Katalog film sederhana dengan fitur pencarian judul, filter genre, dan halaman detail setiap film.',
                'status' => 'draft',
            ],
        ];

        foreach ($projects as $index => $attributes) {
            $project = new Project;
            $project->fill($attributes);

            // created_at diisi manual agar urutan `latest()` stabil
            // tidak semua data punya timestamp yang persis sama
            $project->created_at = now()->subDays($index);
            $project->updated_at = now()->subDays($index);
            $project->save();
        }
    }
}
