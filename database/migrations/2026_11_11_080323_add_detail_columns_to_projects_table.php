<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Kolom detail project untuk Pertemuan 11.
     * description dan status TIDAK ditambah lagi karena sudah ada sejak P9.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('short_description')->nullable()->after('slug');
            $table->date('project_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('project_url')->nullable();
            $table->string('repository_url')->nullable();
        });

        $this->backfillSlugs();
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Index unik dilepas dulu, baru kolomnya dihapus
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'short_description',
                'project_date',
                'is_featured',
                'project_url',
                'repository_url',
            ]);
        });
    }

    /**
     * Isi slug untuk baris yang sudah ada (termasuk yang soft-deleted).
     * Slug bentrok diberi sufiks -2, -3, dst.
     */
    private function backfillSlugs(): void
    {
        $usedSlugs = [];

        foreach (DB::table('projects')->orderBy('id')->get(['id', 'title']) as $row) {
            $base = Str::slug($row->title) ?: 'project-'.$row->id;
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$suffix++;
            }

            $usedSlugs[] = $slug;
            DB::table('projects')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};
