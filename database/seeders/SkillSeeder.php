<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Pemrograman Web',
            'Desain Basis Data',
            'Desain Antarmuka',
            'Version Control',
            'Pengujian Perangkat Lunak',
        ];

        foreach ($skills as $name) {
            Skill::firstOrCreate(['name' => $name]);
        }
    }
}
