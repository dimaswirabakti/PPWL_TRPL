<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProjectSeeder extends Seeder
{
    // Tabel dikosongkan lebih dulu agar seeder aman dijalankan berulang kali.
    // MySQL menolak truncate pada tabel yang dirujuk foreign key, jadi
    // pengecekan foreign key dimatikan sebentar selama truncate.
    public function run(): void
    {
        Schema::withoutForeignKeyConstraints(function () {
            ProjectImage::truncate();
            Project::truncate();
        });

        $projects = [
            [
                'title' => 'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
                'description' => 'Aplikasi mobile untuk pemetaan sampah laut berbasis laporan warga, lengkap dengan peta sebaran dan statistik per wilayah.',
                'status' => 'published',
                'category' => 'Mobile',
            ],
            [
                'title' => 'Website Reservasi Kos (KosKita)',
                'description' => 'Website untuk mencari dan memesan kos dengan filter harga, fasilitas, serta jarak ke kampus.',
                'status' => 'published',
                'category' => 'Web',
            ],
            [
                'title' => 'Katalog Film (Movix)',
                'description' => 'Katalog film sederhana dengan fitur pencarian judul, filter genre, dan halaman detail setiap film.',
                'status' => 'draft',
                'category' => 'Web',
            ],
        ];

        foreach ($projects as $index => $attributes) {
            $project = new Project;
            $project->fill([
                'title' => $attributes['title'],
                'description' => $attributes['description'],
                'status' => $attributes['status'],
                'category_id' => Category::where('name', $attributes['category'])->value('id'),
            ]);

            // created_at diisi manual agar urutan `latest()` stabil
            // tidak semua data punya timestamp yang persis sama
            $project->created_at = now()->subDays($index);
            $project->updated_at = now()->subDays($index);
            $project->save();
        }
    }
}
