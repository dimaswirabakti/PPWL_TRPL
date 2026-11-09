<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Hapus bagian ->after('category_id')
            $table->string('thumbnail')->nullable();
            
            // Karena modul pertemuan 10 ini juga butuh category_id (untuk dropdown form), 
            // sekalian kita tambahkan saja kolomnya di sini jika memang belum ada.
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['thumbnail', 'category_id']);
        });
    }
};