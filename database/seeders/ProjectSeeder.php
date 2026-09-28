<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title'       => 'Aplikasi Pemetaan Sampah Laut (Cleanesia)',
            'description' => 'Aplikasi mobile untuk pemetaan sampah laut.',
        ]);

        Project::create([
            'title'       => 'Website Reservasi Kos (KosKita)',
            'description' => 'Website untuk mencari dan memesan kos.',
        ]);

        Project::create([
            'title'       => 'Katalog Film (Movix)',
            'description' => 'Katalog film sederhana dengan fitur pencarian.',
        ]);
    }
}