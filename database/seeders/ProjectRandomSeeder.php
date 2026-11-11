<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectRandomSeeder extends Seeder
{
    private const JUMLAH = 25;

    private const JENIS = [
        'Aplikasi',
        'Sistem',
        'Dashboard',
        'Platform',
        'Website',
        'Portal',
        'Layanan',
    ];

    private const DOMAIN = [
        'Presensi Mahasiswa',
        'Peminjaman Perpustakaan',
        'Inventaris Laboratorium',
        'Pengaduan Warga',
        'Donasi Bencana',
        'Monitoring Tanaman',
        'Arsip Surat Digital',
        'Penjadwalan Ruang',
        'Pendaftaran Lomba',
        'Rekap Keuangan Organisasi',
    ];

    private const FOKUS = [
        'autentikasi berbasis peran',
        'laporan yang bisa diekspor',
        'notifikasi otomatis',
        'pencarian dan filter lanjutan',
        'antarmuka yang ramah perangkat mobile',
        'riwayat perubahan data',
    ];

    public function run(): void
    {
        $categoryIds = Category::pluck('id');
        $skillIds = Skill::pluck('id');
        $technologyIds = Technology::pluck('id');

        // Slug yang sudah dipakai (misalnya oleh ProjectSeeder), termasuk yang soft-deleted
        $usedSlugs = Project::withTrashed()->pluck('slug')->filter()->all();

        foreach ($this->judulAcak() as $index => $judul) {
            $slug = $this->slugUnik($judul, $usedSlugs);
            $usedSlugs[] = $slug;

            $project = new Project;
            $project->title = $judul;
            $project->slug = $slug;
            $project->short_description = $this->deskripsiSingkat($judul);
            $project->description = $this->deskripsi($judul);
            $project->status = random_int(1, 10) <= 7 ? 'published' : 'draft';
            $project->category_id = $categoryIds->random();

            // Tanggal acak dalam 2 tahun terakhir; sekitar 10% dibiarkan kosong
            $project->project_date = random_int(1, 10) === 1 ? null : now()->subDays(random_int(0, 730))->toDateString();

            // Sekitar 15% project ditandai unggulan
            $project->is_featured = random_int(1, 100) <= 15;

            // URL boleh kosong
            $project->project_url = random_int(0, 1) ? "https://example.com/demo/{$slug}" : null;
            $project->repository_url = random_int(0, 1) ? "https://example.com/repo/{$slug}" : null;

            $project->created_at = now()->subDays($index + 4)->subHours(random_int(0, 23));
            $project->updated_at = $project->created_at;
            $project->save();

            // 1-3 skill acak
            $project->skills()->sync($skillIds->random(min(random_int(1, 3), $skillIds->count())));

            // 1-3 technology acak
            $project->technologies()->sync($technologyIds->random(min(random_int(1, 3), $technologyIds->count())));
        }
    }

    /**
     * Slug dari judul; jika sudah dipakai diberi sufiks -2, -3, dst.
     *
     * @param  list<string>  $usedSlugs
     */
    private function slugUnik(string $judul, array $usedSlugs): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $suffix = 2;

        while (in_array($slug, $usedSlugs, true)) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * @return list<string>
     */
    private function judulAcak(): array
    {
        $kombinasi = [];

        foreach (self::JENIS as $jenis) {
            foreach (self::DOMAIN as $domain) {
                $kombinasi[] = "{$jenis} {$domain}";
            }
        }

        shuffle($kombinasi);

        return array_slice($kombinasi, 0, self::JUMLAH);
    }

    private function deskripsiSingkat(string $judul): string
    {
        $fokus = self::FOKUS[array_rand(self::FOKUS)];

        return "{$judul} dengan {$fokus}.";
    }

    private function deskripsi(string $judul): string
    {
        $fokus = self::FOKUS[array_rand(self::FOKUS)];

        return "{$judul} dibangun sebagai studi kasus pemrograman web dengan fokus pada {$fokus}.";
    }
}
