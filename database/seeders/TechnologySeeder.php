<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Laravel', 'MySQL', 'Tailwind CSS', 'Vite', 'Docker'] as $name) {
            // Slug dipakai sebagai nilai filter di URL
            Technology::updateOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }
    }
}
