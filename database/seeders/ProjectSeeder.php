<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectSeeder extends Seeder
{
    // Tabel dikosongkan lebih dulu agar seeder aman dijalankan berulang kali.
    // MySQL menolak truncate pada tabel yang dirujuk foreign key, jadi
    // pengecekan foreign key dimatikan sebentar selama truncate.
    public function run(): void
    {
        Schema::withoutForeignKeyConstraints(function () {
            DB::table('project_skill')->truncate();
            ProjectImage::truncate();
            Project::truncate();
        });

        $projects = [
            [
                'title' => 'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
                'description' => 'Aplikasi mobile untuk pemetaan sampah laut berbasis laporan warga, lengkap dengan peta sebaran dan statistik per wilayah.',
                'status' => 'published',
                'category' => 'Mobile',
                'skills' => ['Desain Antarmuka', 'Desain Basis Data', 'Pengujian Perangkat Lunak'],
            ],
            [
                'title' => 'Website Reservasi Kos (KosKita)',
                'description' => 'Website untuk mencari dan memesan kos dengan filter harga, fasilitas, serta jarak ke kampus.',
                'status' => 'published',
                'category' => 'Web',
                'skills' => ['Pemrograman Web', 'Desain Basis Data', 'Version Control'],
            ],
            [
                'title' => 'Katalog Film (Movix)',
                'description' => 'Katalog film sederhana dengan fitur pencarian judul, filter genre, dan halaman detail setiap film.',
                'status' => 'draft',
                'category' => 'Web',
                'skills' => ['Pemrograman Web', 'Desain Antarmuka'],
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

            // 2-3 skill yang relevan dengan project ini
            $project->skills()->sync(Skill::whereIn('name', $attributes['skills'])->pluck('id'));
        }
    }
}
