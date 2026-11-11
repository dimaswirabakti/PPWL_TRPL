<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Laravel', 'MySQL', 'Tailwind CSS', 'Vite', 'Docker'] as $name) {
            Technology::firstOrCreate(['name' => $name]);
        }
    }
}
