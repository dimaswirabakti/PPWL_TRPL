<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

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
        foreach ($this->judulAcak() as $index => $judul) {
            $project = new Project;
            $project->title = $judul;
            $project->description = $this->deskripsi($judul);
            $project->status = random_int(1, 10) <= 7 ? 'published' : 'draft';

            $project->created_at = now()->subDays($index + 4)->subHours(random_int(0, 23));
            $project->updated_at = $project->created_at;
            $project->save();
        }
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

    private function deskripsi(string $judul): string
    {
        $fokus = self::FOKUS[array_rand(self::FOKUS)];

        return "{$judul} dibangun sebagai studi kasus pemrograman web dengan fokus pada {$fokus}.";
    }
}
