<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // Seed the application's database.
    public function run(): void
    {
        // firstOrCreate dipakai agar `db:seed` yang dijalankan dua kali tidak
        // memicu error SQLSTATE[23000] 1062 Duplicate entry pada kolom email
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            ProjectSeeder::class,
            ProjectRandomSeeder::class,
        ]);
    }
}
