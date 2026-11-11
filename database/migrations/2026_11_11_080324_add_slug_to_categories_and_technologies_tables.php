<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Slug untuk filter URL, contoh ?category=web dan ?technology=tailwind-css.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        Schema::table('technologies', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        $this->backfillSlugs('categories');
        $this->backfillSlugs('technologies');
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });

        Schema::table('technologies', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    /**
     * Isi slug dari kolom name untuk baris yang sudah ada.
     * Slug bentrok diberi sufiks -2, -3, dst.
     */
    private function backfillSlugs(string $tableName): void
    {
        $usedSlugs = [];

        foreach (DB::table($tableName)->orderBy('id')->get(['id', 'name']) as $row) {
            $base = Str::slug($row->name) ?: 'item-'.$row->id;
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$suffix++;
            }

            $usedSlugs[] = $slug;
            DB::table($tableName)->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};
