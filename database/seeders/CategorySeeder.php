<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Web', 'Mobile', 'Desktop', 'Data'] as $name) {
            // Slug dipakai sebagai nilai filter di URL
            Category::updateOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }
    }
}
