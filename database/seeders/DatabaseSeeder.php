<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        User::firstOrNew(['email' => 'admin@example.com'])
            ->forceFill([
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ])
            ->save();

        $this->call([
            ProjectSeeder::class,
            ProjectRandomSeeder::class,
        ]);
    }
}
