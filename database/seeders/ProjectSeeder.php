<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    // Tabel dikosongkan lebih dulu agar seeder aman dijalankan berulang kali.
    // MySQL menolak truncate pada tabel yang dirujuk foreign key, jadi
    // pengecekan foreign key dimatikan sebentar selama truncate.
    public function run(): void
    {
        Schema::withoutForeignKeyConstraints(function () {
            DB::table('project_skill')->truncate();
            DB::table('project_technology')->truncate();
            ProjectImage::truncate();
            Project::truncate();
        });

        $projects = [
            [
                'title' => 'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
                'short_description' => 'Aplikasi mobile untuk memetakan sampah laut dari laporan warga, dengan API Laravel.',
                'description' => 'Aplikasi mobile untuk pemetaan sampah laut berbasis laporan warga, lengkap dengan peta sebaran dan statistik per wilayah.',
                'status' => 'published',
                'category' => 'Mobile',
                'project_date' => now()->subMonths(3)->toDateString(),
                'is_featured' => true,
                'project_url' => 'https://example.com/demo/cleanesia',
                'repository_url' => 'https://example.com/repo/cleanesia',
                'skills' => ['Desain Antarmuka', 'Desain Basis Data', 'Pengujian Perangkat Lunak'],
                'technologies' => ['Laravel', 'MySQL', 'Docker'],
            ],
            [
                'title' => 'Website Reservasi Kos (KosKita)',
                'short_description' => 'Website pencarian dan pemesanan kos di sekitar kampus berbasis Laravel.',
                'description' => 'Website untuk mencari dan memesan kos dengan filter harga, fasilitas, serta jarak ke kampus.',
                'status' => 'published',
                'category' => 'Web',
                'project_date' => now()->subMonths(8)->toDateString(),
                'is_featured' => true,
                'project_url' => 'https://example.com/demo/koskita',
                'repository_url' => null,
                'skills' => ['Pemrograman Web', 'Desain Basis Data', 'Version Control'],
                'technologies' => ['Laravel', 'MySQL', 'Tailwind CSS', 'Vite'],
            ],
            [
                'title' => 'Katalog Film (Movix)',
                'short_description' => 'Katalog film dengan pencarian judul dan filter genre.',
                'description' => 'Katalog film sederhana dengan fitur pencarian judul, filter genre, dan halaman detail setiap film.',
                'status' => 'draft',
                'category' => 'Web',
                'project_date' => null,
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => 'https://example.com/repo/movix',
                'skills' => ['Pemrograman Web', 'Desain Antarmuka'],
                'technologies' => ['Tailwind CSS', 'Vite'],
            ],
        ];

        foreach ($projects as $index => $attributes) {
            $project = new Project;
            $project->fill([
                'title' => $attributes['title'],
                'slug' => Str::slug($attributes['title']),
                'short_description' => $attributes['short_description'],
                'description' => $attributes['description'],
                'status' => $attributes['status'],
                'category_id' => Category::where('name', $attributes['category'])->value('id'),
                'project_date' => $attributes['project_date'],
                'is_featured' => $attributes['is_featured'],
                'project_url' => $attributes['project_url'],
                'repository_url' => $attributes['repository_url'],
            ]);

            // created_at diisi manual agar urutan `latest()` stabil
            // tidak semua data punya timestamp yang persis sama
            $project->created_at = now()->subDays($index);
            $project->updated_at = now()->subDays($index);
            $project->save();

            // 2-3 skill yang relevan dengan project ini
            $project->skills()->sync(Skill::whereIn('name', $attributes['skills'])->pluck('id'));

            // 2-4 technology yang dipakai project ini
            $project->technologies()->sync(Technology::whereIn('name', $attributes['technologies'])->pluck('id'));
        }
    }
}
